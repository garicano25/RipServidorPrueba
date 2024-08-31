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
use App\modelos\reportes\reportequimicoscatalogoModel;
use App\modelos\reportes\reportequimicosModel;
use App\modelos\reportes\reportequimicosproyectoModel;
//-----------------------
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportequimicoscategoriaModel;
use App\modelos\reportes\reportequimicosareaModel;
use App\modelos\reportes\reportequimicosareacategoriaModel;
use App\modelos\reportes\reportequimicoseppModel;
use App\modelos\reportes\reportequimicosevaluacionModel;
use App\modelos\reportes\reportequimicosevaluacionparametroModel;
use App\modelos\reportes\reportequimicosmetodomuestreoModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reportequimicosparametroscatalogoModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\catreportequimicospartidasModel;
use App\modelos\reportes\reportequimicosgruposModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;


class reportequimicoswordController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace("QUIMICOS_NOMBRE", $quimicos, $texto);

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
    public function reportequimicosword(Request $request)
    {
        try {
            // dd($request->all());

            function sanitizeText($text)
            {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }


            $proyecto_id = $request->proyecto_id;
            $reporteregistro_id = $request->reporteregistro_id;
            $partida_id = $request->partida_id;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL, "es_MX");

            ################ DATOS GENERALES ######################
            $agente_id = 15;
            $agente_nombre = "Químicos";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reportequimicosModel::where('proyecto_id', $proyecto_id)->get();
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

            if ($reporteregistro_id > 0) {
                $reporte  = reportequimicosModel::findOrFail($reporteregistro_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe llenar los datos para poder generarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_quimicos_sin_MEL.docx')); //Ruta carpeta storage
            } else {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_quimicos_sin_MEL.docx')); //Ruta carpeta storage
            }



            ################ PORTADA EXTERNA ####################
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 15) // Quimicos
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
            $plantillaword->setValue('instalación_portada', $recsensorial->recsensorial_instalacion);

            $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportequimicos_fecha;
            $plantillaword->setValue('lugar_fecha_portada', $recsensorial->recsensorial_direccion . ' ' . $fecha);
            $plantillaword->setValue(
                'PORTADA_FECHA',
                $fecha
            );


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

            ##### INTRODUCCION ###################

            $introduccionTexto = $agente[0]->reportequimicos_introduccion;
            $introduccionTextoModificado = $this->introduccion($proyecto, $introduccionTexto);

            // Asigna el texto modificado a la plantilla
            $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);

            if (($revision->reporterevisiones_revision + 0) > 0) {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
            } else {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
            }






            if (($partida_id + 0) > 0) {
                // $partida = catreportequimicospartidasModel::findOrFail($partida_id);
                // $partida = clientepartidasModel::findOrFail($partida_id);


                if (($revision->reporterevisiones_revision + 0) > 0) {
                    // $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' Rev-'.$revision->reporterevisiones_revision.' ('.$partida->clientepartidas_descripcion.')');
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' - Rev-' . $revision->reporterevisiones_revision);
                } else {
                    // $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' ('.$partida->clientepartidas_descripcion.')');
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
                }


                $parametros = DB::select('SELECT
                                                reportequimicosgrupos.proyecto_id,
                                                reportequimicosgrupos.registro_id,
                                                reportequimicosgrupos.proveedor_id,
                                                reportequimicosgrupos.catreportequimicospartidas_id,
                                                reportequimicosproyecto.reportequimicosproyecto_parametro AS parametro,
                                                (
                                                    CASE
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Benceno%" THEN 1
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Tolueno%" THEN 2
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Xileno%" THEN 3
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Hexano%" THEN 1
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Ciclohexano%" THEN 2
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Metano%" THEN 1
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Etano%" THEN 2
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Propano%" THEN 3
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Butano%" THEN 4
                                                        WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Pentano%" THEN 5
                                                        ELSE 0
                                                    END
                                                ) AS orden
                                            FROM
                                                reportequimicosgrupos
                                                LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                            WHERE
                                                reportequimicosgrupos.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosgrupos.registro_id = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                orden ASC,
                                                reportequimicosproyecto.reportequimicosproyecto_parametro ASC');


                if (count($parametros) > 0) {
                    $proveedor_id = ($parametros[0]->proveedor_id + 0);
                } else {
                    $proveedor_id = 0;
                }
            } else {
                // $plantillaword->setValue('PARTIDA', '--- PARTIDA NO SELECCIONADA ---');


                if (($revision->reporterevisiones_revision + 0) > 0) {
                    // $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' (PARTIDA NO SELECCIONADA) Rev-'.$revision->reporterevisiones_revision);
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' - Rev-' . $revision->reporterevisiones_revision);
                } else {
                    // $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' (PARTIDA NO SELECCIONADA)');
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
                }


                $parametros = DB::select('SELECT
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                (
                                                    CASE
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Benceno%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Tolueno%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Xileno%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Hexano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Ciclohexano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Metano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Etano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Propano%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Butano%" THEN 4
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Pentano%" THEN 5
                                                        ELSE 0
                                                    END
                                                ) AS orden
                                            FROM
                                                reportequimicosevaluacion
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                            GROUP BY
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro 
                                            ORDER BY
                                                orden ASC,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro ASC');


                $proveedor_id = 0;
            }


            $quimicos = '';
            foreach ($parametros as $key => $value) {
                if (($key + 0) == 0) {
                    $quimicos = $value->parametro;
                } else {
                    $quimicos .= ', ' . $value->parametro;
                }
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


            $sql = DB::select('SELECT
                                    -- TABLA.id,
                                    -- TABLA.agente_id,
                                    -- TABLA.agente_nombre,
                                    -- TABLA.catactivo_id,
                                    -- TABLA.concepto,
                                    -- CONCAT(": ", TABLA.descripcion) AS descripcion,
                                    -- TABLA.fuente
                                    REPLACE(REPLACE(REPLACE(TABLA.fuente, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS fuente
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
                                    TABLA.fuente ASC');


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
                                            -- TABLA.concepto,
                                            REPLACE(REPLACE(REPLACE(TABLA.concepto, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS concepto,
                                            -- CONCAT(": ", TABLA.descripcion) AS descripcion,
                                            CONCAT(": ", REPLACE(REPLACE(REPLACE(TABLA.descripcion, "<", "˂"), ">", "˃"), "&", "Ꞩ")) AS descripcion,
                                            -- TABLA.fuente
                                            REPLACE(REPLACE(REPLACE(TABLA.fuente, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS fuente
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


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_objetivoespecifico));


            // METODOLOGIA
            //================================================================================


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_metodologia_4_2));


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_ubicacioninstalacion));


            // Imagen FOTO
            if ($reporte->reportequimicos_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reporte->reportequimicos_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporte->reportequimicos_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            // PROCESO INSTALACION
            //================================================================================


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $reporte->reportequimicos_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $reporte->reportequimicos_procesoinstalacion))));


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


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reportequimicosarea_instalacion,
                                        reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                        reportearea.reportearea_orden AS reportequimicosarea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        IF( IFNULL( reportearea.reportearea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportearea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                        reportearea.reportequimicosarea_porcientooperacion,
                                        reportearea.reportearea_caracteristica AS reportequimicosarea_caracteristica,
                                        reportearea.reportearea_maquinaria AS reportequimicosarea_maquinaria,
                                        reportearea.reportearea_contaminante AS reportequimicosarea_contaminante,
                                        reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
                                        reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
                                        reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reportequimicosareacategoria.reportequimicoscategoria_id, "activo", "") AS checked
                                            FROM
                                                reportequimicosareacategoria
                                            WHERE
                                                reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
                                                AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . ' 
                                            LIMIT 1
                                        ), "") AS activo,
                                        reporteareacategoria.reporteareacategoria_total AS reportequimicoscategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh AS reportequimicosareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades,
                                        (
                                            SELECT
                                                reportequimicos.reportequimicos_actividadprincipal 
                                            FROM
                                                reportequimicos
                                            WHERE
                                                reportequimicos.id = ' . $reporteregistro_id . ' 
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
                                        reportequimicosarea.proyecto_id,
                                        reportequimicosarea.registro_id,
                                        reportequimicosareacategoria.id,
                                        REPLACE(reportequimicosarea.reportequimicosarea_instalacion, "\"", "") AS reportequimicosarea_instalacion,
                                        reportequimicosareacategoria.reportequimicosarea_id,
                                        reportequimicosarea.reportequimicosarea_nombre,
                                        reportequimicosarea.reportequimicosarea_numorden,
                                        reportequimicosareacategoria.reportequimicoscategoria_id,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre,
                                        reportequimicoscategoria.reportequimicoscategoria_total,
                                        (
                                            SELECT
                                                reportequimicos.reportequimicos_actividadprincipal 
                                            FROM
                                                reportequimicos
                                            WHERE
                                                reportequimicos.id = reportequimicosarea.registro_id
                                        ) AS actividad_principal_instalacion
                                    FROM
                                        reportequimicosareacategoria
                                        LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                        LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                    WHERE
                                        reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                    ORDER BY
                                        reportequimicosarea.reportequimicosarea_numorden ASC,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 1500;
            $ancho_col_4 = 4000;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 2500;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportequimicosarea_instalacion) {
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
                                                    IFNULL(SUM( TABLA.reportequimicoscategoria_total ), 0) AS total 
                                                FROM
                                                    (
                                                        SELECT
                                                            reportequimicoscategoria.proyecto_id,
                                                            reportequimicoscategoria.registro_id,
                                                            reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                            reportequimicosarea.reportequimicosarea_instalacion,
                                                            reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                            reportequimicoscategoria.reportequimicoscategoria_total 
                                                        FROM
                                                            reportequimicosareacategoria
                                                            LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                                            LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id 
                                                        WHERE
                                                            reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                                            AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                                            AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0
                                                            AND REPLACE(reportequimicosarea.reportequimicosarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                        GROUP BY
                                                            reportequimicoscategoria.proyecto_id,
                                                            reportequimicoscategoria.registro_id,
                                                            reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                            reportequimicosarea.reportequimicosarea_instalacion,
                                                            reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                            reportequimicoscategoria.reportequimicoscategoria_total 
                                                    ) AS TABLA');
                        }


                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
                        $table->addCell($ancho_col_6, $continua_fila);

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);
                    }


                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reportequimicosarea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportequimicosarea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reportequimicosarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $texto);
                //     $instalacion = $value->reportequimicosarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reportequimicosarea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_nombre, $texto);
                    $area = $value->reportequimicosarea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportequimicoscategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reportequimicoscategoria_total, $texto);


                if ($instalacion != $value->reportequimicosarea_instalacion) {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $value->actividad_principal_instalacion), $texto);
                    $instalacion = $value->reportequimicosarea_instalacion;
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
                                        IFNULL(SUM( TABLA.reportequimicoscategoria_total ), 0) AS total 
                                    FROM
                                        (
                                            SELECT
                                                reportequimicoscategoria.proyecto_id,
                                                reportequimicoscategoria.registro_id,
                                                reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                reportequimicosarea.reportequimicosarea_instalacion,
                                                reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                reportequimicoscategoria.reportequimicoscategoria_total 
                                            FROM
                                                reportequimicosareacategoria
                                                LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                                LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id 
                                            WHERE
                                                reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                                AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0
                                                AND REPLACE(reportequimicosarea.reportequimicosarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                            GROUP BY
                                                reportequimicoscategoria.proyecto_id,
                                                reportequimicoscategoria.registro_id,
                                                reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                reportequimicosarea.reportequimicosarea_instalacion,
                                                reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                reportequimicoscategoria.reportequimicoscategoria_total 
                                        ) AS TABLA');
            }


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
            $table->addCell($ancho_col_6, $continua_fila);


            $plantillaword->setComplexBlock('TABLA_5_3', $table);


            // TABLA 5.4 Equipo de Protección Personal (EPP)
            //================================================================================


            $sql = DB::select('SELECT
                                    reportequimicosepp.id,
                                    reportequimicosepp.proyecto_id,
                                    reportequimicosepp.registro_id,
                                    reportequimicosepp.reportequimicosepp_partecuerpo,
                                    reportequimicosepp.reportequimicosepp_equipo 
                                FROM
                                    reportequimicosepp
                                WHERE
                                    reportequimicosepp.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportequimicosepp.registro_id = ' . $reporteregistro_id . ' 
                                ORDER BY
                                    reportequimicosepp.id ASC');


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 3500;
            $ancho_col_2 = 6000;
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Parte del cuerpo', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Equipo de protección personal básico proporcionado', $encabezado_texto);

            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reportequimicosepp_partecuerpo, $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->reportequimicosepp_equipo, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_4', $table);


            // TABLA 5.5 Actividades del personal expuesto
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportequimicosarea_instalacion,
                                        TABLA.reportequimicosarea_nombre,
                                        TABLA.reportequimicosarea_numorden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportequimicosarea_porcientooperacion,
                                        TABLA.reportequimicosarea_caracteristica,
                                        TABLA.reportequimicosarea_maquinaria,
                                        TABLA.reportequimicosarea_contaminante,
                                        TABLA.reportequimicoscategoria_id,
                                        TABLA.reportequimicoscategoria_orden,
                                        TABLA.reportequimicoscategoria_nombre,
                                        TABLA.activo,
                                        TABLA.reportequimicoscategoria_total,
                                        TABLA.reportequimicosareacategoria_geh,
                                        TABLA.reportequimicosareacategoria_actividades
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                                                reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                                reportearea.reportearea_orden AS reportequimicosarea_numorden,
                                                reportearea.reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reportearea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportearea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportequimicosarea_porcientooperacion,
                                                reportearea.reportearea_caracteristica AS reportequimicosarea_caracteristica,
                                                REPLACE(REPLACE(REPLACE(reportearea.reportearea_maquinaria, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_maquinaria,
                                                REPLACE(REPLACE(REPLACE(reportearea.reportearea_contaminante, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_contaminante,
                                                reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
                                                reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
                                                reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reportequimicosareacategoria.reportequimicoscategoria_id, "activo", "") AS checked
                                                    FROM
                                                        reportequimicosareacategoria
                                                    WHERE
                                                        reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
                                                        AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . ' 
                                                    LIMIT 1
                                                ), "") AS activo,
                                                reporteareacategoria.reporteareacategoria_total AS reportequimicoscategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS reportequimicosareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades
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
                                        TABLA.reportequimicosarea_porcientooperacion >= 0
                                        AND TABLA.activo != ""
                                    ORDER BY
                                        TABLA.reportequimicosarea_numorden ASC,
                                        TABLA.reportequimicosarea_nombre ASC,
                                        TABLA.reportequimicoscategoria_orden ASC,
                                        TABLA.reportequimicoscategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reportequimicosarea.proyecto_id,
                                        reportequimicosarea.registro_id,
                                        reportequimicosareacategoria.id,
                                        reportequimicosarea.reportequimicosarea_instalacion,
                                        reportequimicosareacategoria.reportequimicosarea_id,
                                        reportequimicosarea.reportequimicosarea_nombre,
                                        reportequimicosarea.reportequimicosarea_numorden,
                                        reportequimicosareacategoria.reportequimicoscategoria_id,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre,
                                        reportequimicosareacategoria.reportequimicosareacategoria_actividades
                                    FROM
                                        reportequimicosareacategoria
                                        LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                        LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                    WHERE
                                        reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reportequimicosarea.reportequimicosarea_porcientooperacion > 0 
                                        AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0
                                    ORDER BY
                                        reportequimicosarea.reportequimicosarea_numorden ASC,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre ASC');
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
                if ($instalacion != $value->reportequimicosarea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reportequimicosarea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportequimicosarea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reportequimicosarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $texto);
                //     $instalacion = $value->reportequimicosarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reportequimicosarea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_nombre, $texto);
                    $area = $value->reportequimicosarea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportequimicoscategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reportequimicosareacategoria_actividades, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_5', $table);


            // TABLA 5.6 Tabla de identificación de fuentes generadoras
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportequimicosarea_instalacion,
                                        TABLA.reportequimicosarea_nombre,
                                        TABLA.reportequimicosarea_numorden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportequimicosarea_porcientooperacion,
                                        -- TABLA.reportequimicosarea_caracteristica,
                                        IF(TABLA.reportequimicosarea_caracteristica = 1, "Si", "No") AS abierta,
                                        IF(TABLA.reportequimicosarea_caracteristica = 0, "Si", "No") AS cerrada,
                                        TABLA.reportequimicosarea_maquinaria,
                                        TABLA.reportequimicosarea_contaminante,
                                        TABLA.reportequimicoscategoria_id,
                                        TABLA.reportequimicoscategoria_orden,
                                        TABLA.reportequimicoscategoria_nombre,
                                        TABLA.activo,
                                        TABLA.reportequimicoscategoria_total,
                                        TABLA.reportequimicosareacategoria_geh,
                                        TABLA.reportequimicosareacategoria_actividades
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                                                reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                                reportearea.reportearea_orden AS reportequimicosarea_numorden,
                                                reportearea.reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reportearea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportearea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportequimicosarea_porcientooperacion,
                                                reportearea.reportearea_caracteristica AS reportequimicosarea_caracteristica,
                                                REPLACE(REPLACE(REPLACE(reportearea.reportearea_maquinaria, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_maquinaria,
                                                REPLACE(REPLACE(REPLACE(reportearea.reportearea_contaminante, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_contaminante,
                                                reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
                                                reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
                                                reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reportequimicosareacategoria.reportequimicoscategoria_id, "activo", "") AS checked
                                                    FROM
                                                        reportequimicosareacategoria
                                                    WHERE
                                                        reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
                                                        AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . ' 
                                                    LIMIT 1
                                                ), "") AS activo,
                                                reporteareacategoria.reporteareacategoria_total AS reportequimicoscategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS reportequimicosareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades
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
                                        TABLA.reportequimicosarea_porcientooperacion >= 0
                                        AND TABLA.activo != ""
                                    ORDER BY
                                        TABLA.reportequimicosarea_numorden ASC,
                                        TABLA.reportequimicosarea_nombre ASC,
                                        TABLA.reportequimicoscategoria_orden ASC,
                                        TABLA.reportequimicoscategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reportequimicosarea.proyecto_id,
                                        reportequimicosarea.registro_id,
                                        reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                        reportequimicosareacategoria.reportequimicosarea_id,
                                        reportequimicosarea.reportequimicosarea_instalacion,
                                        reportequimicosarea.reportequimicosarea_nombre,
                                        reportequimicosarea.reportequimicosarea_numorden,
                                        reportequimicosarea.reportequimicosarea_porcientooperacion,
                                        -- reportequimicosarea.reportequimicosarea_maquinaria,
                                        REPLACE(REPLACE(REPLACE(reportequimicosarea.reportequimicosarea_maquinaria, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_maquinaria,
                                        -- reportequimicosarea.reportequimicosarea_contaminante,
                                        REPLACE(REPLACE(REPLACE(reportequimicosarea.reportequimicosarea_contaminante, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosarea_contaminante,
                                        IF(reportequimicosarea.reportequimicosarea_caracteristica = 1, "Si", "No") AS abierta,
                                        IF(reportequimicosarea.reportequimicosarea_caracteristica = 0, "Si", "No") AS cerrada,
                                        reportequimicosareacategoria.reportequimicoscategoria_id,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre 
                                    FROM
                                        reportequimicosareacategoria
                                        LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                        LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id 
                                    WHERE
                                        reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reportequimicosarea.reportequimicosarea_porcientooperacion > 0 
                                        AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0
                                    ORDER BY
                                        reportequimicosarea.reportequimicosarea_numorden ASC,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre ASC');
            }


            // encabezado tabla
            $ancho_col_1 = 500;
            $ancho_col_2 = 3000;
            $ancho_col_3 = 5000;
            $ancho_col_4 = 2500;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // Encabezado
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Fuentes generadoras', $encabezado_texto);
            // $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Generación del contaminante', $encabezado_texto);
            // $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Puesto de trabajo', $encabezado_texto);
            // $table->addCell(($ancho_col_5+$ancho_col_6), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Características<w:br/>del área', $encabezado_texto);

            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $continua_fila);
            // $table->addCell($ancho_col_2, $continua_fila);
            // $table->addCell($ancho_col_3, $continua_fila);
            // $table->addCell($ancho_col_4, $continua_fila);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Abierta', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Cerrada', $encabezado_texto);


            $fila = 0;
            $instalacion = 'xxxx';
            $fuente = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportequimicosarea_instalacion) {
                    // Encabezado
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Fuentes generadoras', $encabezado_texto);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Generación del contaminante', $encabezado_texto);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Puesto de trabajo', $encabezado_texto);
                    $table->addCell(($ancho_col_5 + $ancho_col_6), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Características<w:br/>del área', $encabezado_texto);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila);
                    $table->addCell($ancho_col_2, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                    $table->addCell($ancho_col_4, $continua_fila);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Abierta', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Cerrada', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reportequimicosarea_instalacion;
                    $fila = 0;
                }


                $table->addRow(); //fila


                if ($fuente != $value->reportequimicosarea_maquinaria) {
                    $fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($fila, $texto);
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_maquinaria, $texto);
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($justificado)->addText($value->reportequimicosarea_contaminante, $texto);

                    // $fuente = $value->reportequimicosarea_maquinaria;
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                    $table->addCell($ancho_col_2, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportequimicoscategoria_nombre, $texto);


                if ($fuente != $value->reportequimicosarea_maquinaria) {
                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->abierta, $texto);
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->cerrada, $texto);

                    $fuente = $value->reportequimicosarea_maquinaria;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila);
                    $table->addCell($ancho_col_6, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_5_6', $table);


            // TABLA 5.7 Determinación de la prioridad de la(s) sustancia(s) química(s) o mezclas por muestrear
            //================================================================================


            $sql = DB::select("CALL sp_ponderacion1_tabla8_1_b(?)", [$proyecto->recsensorial_id]);


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 2500;
            $ancho_col_2 = 3000;
            $ancho_col_3 = 1500;
            $ancho_col_4 = 1500;
            $ancho_col_5 = 1500;
            $ancho_col_6 = 1500;
            $ancho_col_7 = 1500;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Sustancia química', $encabezado_texto);
            $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Componentes a evaluar', $encabezado_texto);
            $table->addCell(($ancho_col_3 + $ancho_col_4 + $ancho_col_5), array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Valor de ponderación', $encabezado_texto);
            $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('TOTAL<w:br/>(Suma de los valores de<w:br/>ponderación)', $encabezado_texto);
            $table->addCell($ancho_col_7, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Prioridad<w:br/>de muestreo', $encabezado_texto);

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila);
            $table->addCell($ancho_col_2, $continua_fila);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad<w:br/>manejada', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación<w:br/>de riesgo', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Volatilidad', $encabezado_texto);
            $table->addCell($ancho_col_6, $continua_fila);
            $table->addCell($ancho_col_7, $continua_fila);


            $sustancia = 'xxxx';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila


                if ($sustancia != $value->PRODUCTO) {
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->PRODUCTO, $texto);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->COMPONENTE, $texto);



                $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->PONDERACION_CANTIDAD, $texto);
                $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->PONDERACION_CLASIFICACION, $texto);
                $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->PONDERACION_VOLATILIDAD, $texto);
                $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);

                $texto_color = "#000000";
                if ($value->COLOR == "#E74C3C") //Rojo
                {
                    $texto_color = "#FFFFFF";
                }

                $table->addCell($ancho_col_7, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $value->COLOR))->addTextRun($centrado)->addText($value->PRIORIDAD, array('color' => $texto_color, 'size' => 10, 'bold' => true, 'name' => $fuente));

                $sustancia = $value->PRODUCTO;
            }


            $plantillaword->setComplexBlock('TABLA_5_7', $table);


            // TABLA 6.1 Condiciones de operación durante la evaluación (representado en porcentaje)
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                                        reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                        reportearea.reportearea_orden AS reportequimicosarea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        reportearea.reportequimicosarea_porcientooperacion,
                                        reportearea.reportearea_caracteristica,
                                        reportearea.reportearea_maquinaria,
                                        reportearea.reportearea_contaminante 
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportearea.reportequimicosarea_porcientooperacion > 0
                                    ORDER BY
                                        reportearea.reportearea_orden ASC');
            } else {
                $sql = DB::select('SELECT
                                        reportequimicosarea.proyecto_id,
                                        reportequimicosarea.registro_id,
                                        reportequimicosarea.reportequimicosarea_instalacion,
                                        reportequimicosarea.reportequimicosarea_nombre,
                                        reportequimicosarea.reportequimicosarea_numorden,
                                        reportequimicosarea.reportequimicosarea_porcientooperacion 
                                    FROM
                                        reportequimicosarea 
                                    WHERE
                                        reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosarea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reportequimicosarea.reportequimicosarea_porcientooperacion > 0 
                                    ORDER BY
                                        reportequimicosarea.reportequimicosarea_numorden ASC,
                                        reportequimicosarea.reportequimicosarea_instalacion ASC,
                                        reportequimicosarea.reportequimicosarea_nombre ASC');
            }

            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 5500;
            $ancho_col_4 = 3000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // encabezado
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


            $numero_fila = 0;
            $total = 'XXXX';
            $instalacion = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportequimicosarea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reportequimicosarea_instalacion;
                    $numero_fila = 0;
                }


                $numero_fila += 1;
                $table->addRow(); //fila


                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);


                // if($instalacion != $value->reportequimicosarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_instalacion, $texto);
                //     $instalacion = $value->reportequimicosarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportequimicosarea_nombre, $texto);


                if ($instalacion != $value->reportequimicosarea_instalacion || $total != $value->reportequimicosarea_porcientooperacion) {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reportequimicosarea_porcientooperacion . '%', $texto);


                    $instalacion = $value->reportequimicosarea_instalacion;
                    $total = $value->reportequimicosarea_porcientooperacion;
                } else {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_6_1', $table);


            // TABLA 7 Resultados
            //================================================================================

            $where_condicion = '';
            foreach ($parametros as $key => $value) {
                if (($key + 0) == 0) {
                    $where_condicion = 'WHERE TABLA.parametro = "' . $value->parametro . '" ';
                } else {
                    $where_condicion .= 'OR TABLA.parametro = "' . $value->parametro . '" ';
                }
            }


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.area_nombre,
                                        TABLA.categoria_nombre,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto,
                                        TABLA.parametro_id,
                                        CONCAT(": ", TABLA.parametro) AS parametro,
                                        CONCAT(": ", REPLACE(TABLA.metodo, "&", "Ց")) AS metodo,
                                        REPLACE(REPLACE(TABLA.concentracion_texto, "<", "˂"), ">", "˃") AS concentracion_texto,
                                        REPLACE(REPLACE(TABLA.concentracion, "<", "˂"), ">", "˃") AS concentracion,
                                        REPLACE(REPLACE(TABLA.valorlimite, "<", "˂"), ">", "˃") AS valorlimite,
                                        REPLACE(REPLACE(TABLA.limitesuperior, "<", "˂"), ">", "˃") AS limitesuperior,
                                        -- TABLA.concentracion_texto,
                                        -- TABLA.concentracion,
                                        -- TABLA.valorlimite,
                                        -- TABLA.limitesuperior,
                                        TABLA.periodo,
                                        TABLA.resultado_texto,
                                        TABLA.resultado_color,
                                        TABLA.concentracion_texto,
                                        TABLA.valorlimiteTexto, 
                                        TABLA.limitesuperiorTexto,
                                        (
                                            CASE
                                                WHEN TABLA.parametro LIKE "Benceno%" THEN 1
                                                WHEN TABLA.parametro LIKE "Tolueno%" THEN 2
                                                WHEN TABLA.parametro LIKE "Xileno%" THEN 3
                                                WHEN TABLA.parametro LIKE "Hexano%" THEN 1
                                                WHEN TABLA.parametro LIKE "Ciclohexano%" THEN 2
                                                WHEN TABLA.parametro LIKE "Metano%" THEN 1
                                                WHEN TABLA.parametro LIKE "Etano%" THEN 2
                                                WHEN TABLA.parametro LIKE "Propano%" THEN 3
                                                WHEN TABLA.parametro LIKE "Butano%" THEN 4
                                                WHEN TABLA.parametro LIKE "Pentano%" THEN 5
                                                ELSE 0
                                            END
                                        ) AS orden
                                    FROM
                                        (
                                            SELECT
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.reportequimicosarea_id,
                                                reportearea.reportearea_nombre AS area_nombre,
                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                IFNULL(reportecategoria.reportecategoria_nombre, "N/A") AS categoria_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre AS nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha AS ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo AS geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total AS total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                reportequimicosevaluacionparametro.id AS parametro_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_metodo AS metodo,
                                              

                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS concentracion_texto,
                                                -- (REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) AS concentracion,
                                                (REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) AS concentracion,

                                                 CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS valorlimiteTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS valorlimite,
                                                
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior, " ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS limitesuperiorTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior AS limitesuperior,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_periodo AS periodo,
                                                -- IF((REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                -- IF((REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN reportearea ON reportequimicosevaluacion.reportequimicosarea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportecategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        ) AS TABLA
                                    ' . $where_condicion . ' 
                                    -- WHERE
                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                        -- TABLA.parametro = "Metano"
                                        -- OR TABLA.parametro = "Propano"
                                        -- OR TABLA.parametro = "Butano"
                                        -- OR TABLA.parametro = "Pentano"
                                        -- OR TABLA.parametro = "Etano"
                                    ORDER BY
                                        orden ASC,
                                        TABLA.parametro ASC,
                                        TABLA.punto ASC');
            } else {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.area_nombre,
                                        TABLA.categoria_nombre,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto,
                                        TABLA.parametro_id,
                                        CONCAT(": ", TABLA.parametro) AS parametro,
                                        CONCAT(": ", REPLACE(TABLA.metodo, "&", "Ց")) AS metodo,
                                        REPLACE(REPLACE(TABLA.concentracion_texto, "<", "˂"), ">", "˃") AS concentracion_texto,
                                        REPLACE(REPLACE(TABLA.concentracion, "<", "˂"), ">", "˃") AS concentracion,
                                        REPLACE(REPLACE(TABLA.valorlimite, "<", "˂"), ">", "˃") AS valorlimite,
                                        REPLACE(REPLACE(TABLA.limitesuperior, "<", "˂"), ">", "˃") AS limitesuperior,
                                        -- TABLA.concentracion_texto,
                                        -- TABLA.concentracion,
                                        -- TABLA.valorlimite,
                                        -- TABLA.limitesuperior,
                                        TABLA.periodo,
                                        TABLA.resultado_texto,
                                        TABLA.resultado_color,
                                        TABLA.valorlimiteTexto, 
                                        TABLA.concentracion_texto,
                                        TABLA.limitesuperiorTexto,
                                        (
                                            CASE
                                                WHEN TABLA.parametro LIKE "Benceno%" THEN 1
                                                WHEN TABLA.parametro LIKE "Tolueno%" THEN 2
                                                WHEN TABLA.parametro LIKE "Xileno%" THEN 3
                                                WHEN TABLA.parametro LIKE "Hexano%" THEN 1
                                                WHEN TABLA.parametro LIKE "Ciclohexano%" THEN 2
                                                WHEN TABLA.parametro LIKE "Metano%" THEN 1
                                                WHEN TABLA.parametro LIKE "Etano%" THEN 2
                                                WHEN TABLA.parametro LIKE "Propano%" THEN 3
                                                WHEN TABLA.parametro LIKE "Butano%" THEN 4
                                                WHEN TABLA.parametro LIKE "Pentano%" THEN 5
                                                ELSE 0
                                            END
                                        ) AS orden
                                    FROM
                                        (
                                            SELECT
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.reportequimicosarea_id,
                                                reportequimicosarea.reportequimicosarea_nombre AS area_nombre,
                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                IFNULL(reportequimicoscategoria.reportequimicoscategoria_nombre, "N/A") AS categoria_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre AS nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha AS ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo AS geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total AS total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                reportequimicosevaluacionparametro.id AS parametro_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_metodo AS metodo,
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS concentracion_texto,
                                                -- (REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) AS concentracion,
                                                (REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) AS concentracion,
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS valorlimiteTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS valorlimite,
                                                
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior, " ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS limitesuperiorTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior AS limitesuperior,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_periodo AS periodo,
                                                -- IF((REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                -- IF((REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN reportequimicosarea ON reportequimicosevaluacion.reportequimicosarea_id = reportequimicosarea.id
                                                LEFT JOIN reportequimicoscategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        ) AS TABLA
                                    ' . $where_condicion . ' 
                                    -- WHERE
                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                        -- TABLA.parametro = "Metano"
                                        -- OR TABLA.parametro = "Propano"
                                        -- OR TABLA.parametro = "Butano"
                                        -- OR TABLA.parametro = "Pentano"
                                        -- OR TABLA.parametro = "Etano"
                                    ORDER BY
                                        orden ASC,
                                        TABLA.parametro ASC,
                                        TABLA.punto ASC');
            }


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 2000;
            $ancho_col_3 = 2500;
            $ancho_col_4 = 1500;
            $ancho_col_5 = 1500;
            $ancho_col_6 = 1500;
            $ancho_col_7 = 1500;
            $ancho_col_8 = 1500;

            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell(13000, array('gridSpan' => 8, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('Parámetro: ', $texto);
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell(13000, array('gridSpan' => 8, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('Método: ', $texto);
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de evaluación', $encabezado_texto);
            // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Concentración medida del ambiente (ppm)', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Valor límite de exposición (ppm)', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite superior de confianza (ppm)', $encabezado_texto);
            // $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Periodo de muestreo y evaluación', $encabezado_texto);
            // $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            $parametro = 'XXXX';
            $area = 'XXXX';
            foreach ($sql as $key => $value) {
                if ($parametro != $value->parametro) {
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 8, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Parámetro</w:t></w:r><w:r><w:t>' . $value->parametro, $texto);
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 8, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Método</w:t></w:r><w:r><w:t>' . $value->metodo, $texto);
                    $table->addRow(); //fila
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de evaluación', $encabezado_texto);
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Concentración medida del ambiente', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Valor límite de exposición', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite superior de confianza', $encabezado_texto);
                    $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Periodo de muestreo y evaluación', $encabezado_texto);
                    $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);

                    $parametro = $value->parametro;
                }


                $table->addRow(); //fila


                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText(sanitizeText($value->punto), $texto);


                if ($area != $value->area_nombre) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->area_nombre), $texto);
                    $area = $value->area_nombre;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText(sanitizeText($value->categoria_nombre), $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText(sanitizeText($value->concentracion_texto), $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText(sanitizeText($value->valorlimiteTexto), $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText(sanitizeText($value->limitesuperiorTexto), $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText(sanitizeText($value->periodo, $texto));


                $texto_color = "#000000";
                if ($value->resultado_color == "#FF0000") //Rojo
                {
                    $texto_color = "#FFFFFF";
                }


                $table->addCell($ancho_col_8, array('bgColor' => $value->resultado_color))->addTextRun($centrado)->addText($value->resultado_texto, array('color' => $texto_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
            }


            $plantillaword->setComplexBlock('TABLA_7', $table);


            // TABLA 7.1 Método de muestreo
            //================================================================================


            $where_condicion = '';


            foreach ($parametros as $key => $value) {
                if (($key + 0) == 0) {
                    $where_condicion = ' AND (reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = "' . $value->parametro . '" ';
                } else {
                    $where_condicion .= 'OR reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = "' . $value->parametro . '" ';
                }
            }


            if (count($parametros) > 0) {
                $where_condicion .= ') ';
            }


            $sql = DB::select('SELECT
                                    reportequimicosmetodomuestreo.proyecto_id, 
                                    reportequimicosmetodomuestreo.registro_id, 
                                    (
                                        CASE
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Benceno%" THEN 1
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Tolueno%" THEN 2
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Xileno%" THEN 3
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Hexano%" THEN 1
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Ciclohexano%" THEN 2
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Metano%" THEN 1
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Etano%" THEN 2
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Propano%" THEN 3
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Butano%" THEN 4
                                            WHEN reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro LIKE "Pentano%" THEN 5
                                            ELSE 0
                                        END
                                    ) AS orden,
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro AS parametro, 
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_puntos AS puntos, 
                                    -- reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_metodo AS metodo, 
                                    REPLACE(reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_metodo, "&", "Ց") AS metodo,
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_tipo AS tipo, 
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_orden, 
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_flujo AS flujos
                                FROM
                                    reportequimicosmetodomuestreo
                                WHERE
                                    reportequimicosmetodomuestreo.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportequimicosmetodomuestreo.registro_id = ' . $reporteregistro_id . ' 
                                    ' . $where_condicion . '
                                ORDER BY
                                    orden ASC,
                                    reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro ASC');


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 3500;
            $ancho_col_2 = 5500;
            $ancho_col_3 = 1000;
            $ancho_col_4 = 1500;
            $ancho_col_5 = 1500;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
            $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Procedimiento o método', $encabezado_texto);
            $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Puntos evaluados', $encabezado_texto);
            $table->addCell(($ancho_col_4 + $ancho_col_5), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '#0C3F64'))->addTextRun($centrado)->addText('Datos del muestreo: ', $encabezado_texto);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila);
            $table->addCell($ancho_col_2, $continua_fila);
            $table->addCell($ancho_col_3, $continua_fila);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de Muestra', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Flujo de muestreo ', $encabezado_texto);


            $parametro = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila


                if ($parametro != $value->parametro) {
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->parametro, $texto);
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->metodo, $texto);
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->puntos, $texto);
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->tipo, $texto);

                    $parametro = $value->parametro;
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                    $table->addCell($ancho_col_2, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                    $table->addCell($ancho_col_4, $continua_fila);
                }


                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->flujos, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_7_1', $table);


            // TABLA 7.2 Matriz de exposición laboral
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


            $where_condicion = '';
            foreach ($parametros as $key => $value) {
                if (($key + 0) == 0) {
                    $where_condicion = ' WHERE TABLA.parametro = "' . $value->parametro . '" ';
                } else {
                    $where_condicion .= 'OR TABLA.parametro = "' . $value->parametro . '" ';
                }
            }


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.id,
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.catregion_nombre,
                                        TABLA.catsubdireccion_nombre,
                                        TABLA.catgerencia_nombre,
                                        TABLA.catactivo_nombre,
                                        TABLA.gerencia_activo,
                                        TABLA.instalacion,
                                        TABLA.area,
                                        TABLA.categoria,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto
                                        -- TABLA.parametro
                                    FROM
                                        (
                                            SELECT
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
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
                                                reportearea.reportearea_instalacion AS instalacion,
                                                reportearea.reportearea_nombre AS area,
                                                IFNULL(reportecategoria.reportecategoria_nombre, "N/A") AS categoria,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre AS nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha AS ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo AS geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total AS total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro 
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN proyecto ON reportequimicosevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportequimicosevaluacion.reportequimicosarea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportecategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id 
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC
                                        ) AS TABLA
                                    ' . $where_condicion . ' 
                                    -- WHERE
                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                        -- TABLA.parametro = "Metano"
                                        -- OR TABLA.parametro = "Etano"
                                        -- OR TABLA.parametro = "Propano"
                                        -- OR TABLA.parametro = "Butano"
                                        -- OR TABLA.parametro = "Pentano"
                                    GROUP BY
                                        TABLA.id,
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.catregion_nombre,
                                        TABLA.catsubdireccion_nombre,
                                        TABLA.catgerencia_nombre,
                                        TABLA.catactivo_nombre,
                                        TABLA.gerencia_activo,
                                        TABLA.instalacion,
                                        TABLA.area,
                                        TABLA.categoria,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto
                                    ORDER BY
                                        TABLA.punto ASC');
            } else {
                $sql = DB::select('SELECT
                                        TABLA.id,
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.catregion_nombre,
                                        TABLA.catsubdireccion_nombre,
                                        TABLA.catgerencia_nombre,
                                        TABLA.catactivo_nombre,
                                        TABLA.gerencia_activo,
                                        TABLA.instalacion,
                                        TABLA.area,
                                        TABLA.categoria,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto
                                        -- TABLA.parametro
                                    FROM
                                        (
                                            SELECT
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
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
                                                reportequimicosarea.reportequimicosarea_instalacion AS instalacion,
                                                reportequimicosarea.reportequimicosarea_nombre AS area,
                                                IFNULL(reportequimicoscategoria.reportequimicoscategoria_nombre, "N/A") AS categoria,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre AS nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha AS ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo AS geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total AS total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro 
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN proyecto ON reportequimicosevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportequimicosarea ON reportequimicosevaluacion.reportequimicosarea_id = reportequimicosarea.id
                                                LEFT JOIN reportequimicoscategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id 
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC
                                        ) AS TABLA
                                    ' . $where_condicion . ' 
                                    -- WHERE
                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                        -- TABLA.parametro = "Metano"
                                        -- OR TABLA.parametro = "Etano"
                                        -- OR TABLA.parametro = "Propano"
                                        -- OR TABLA.parametro = "Butano"
                                        -- OR TABLA.parametro = "Pentano"
                                    GROUP BY
                                        TABLA.id,
                                        TABLA.proyecto_id,
                                        TABLA.registro_id,
                                        TABLA.catregion_nombre,
                                        TABLA.catsubdireccion_nombre,
                                        TABLA.catgerencia_nombre,
                                        TABLA.catactivo_nombre,
                                        TABLA.gerencia_activo,
                                        TABLA.instalacion,
                                        TABLA.area,
                                        TABLA.categoria,
                                        TABLA.nombre,
                                        TABLA.ficha,
                                        TABLA.geo,
                                        TABLA.total,
                                        TABLA.punto
                                    ORDER BY
                                        TABLA.punto ASC');
            }


            $numero_fila = 0;
            if (($proyecto->catregion_id + 0) == 1) //REGION NORTE
            {
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 300; // 500;
                $ancho_col_2 = 4000; // 1250;
                $ancho_col_3 = 4000; // 1250;
                $ancho_col_4 = 4700; // 1250;
                $ancho_col_5 = NULL; // 1250;
                $ancho_col_6 = NULL; // 2000;
                $ancho_col_7 = NULL; // 1000;
                $ancho_col_8 = NULL; // 2000;
                $ancho_col_9 = NULL; // 1000;
                $ancho_col_10 = NULL; // 1000;
                $ancho_col_11 = NULL; // 500;
                $ancho_col_12 = NULL; // 500;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell($ancho_col_2, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell($ancho_col_3, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                $table->addCell($ancho_col_4, array('gridSpan' => (count($parametros) * 2), 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes químicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell($ancho_col_3, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                $table->addCell($ancho_col_4, array('gridSpan' => (count($parametros) * 2), 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('COV´S', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o corporativo', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia o activo', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de referencia en atlas de riesgo', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de personas', $encabezado_texto3);
                $table->addCell(NULL, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de exposición homogénea', $encabezado_texto3);
                foreach ($parametros as $key => $value) {
                    $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText($value->parametro, $encabezado_texto3);
                }

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                $table->addCell(NULL, $continua_fila3);
                foreach ($parametros as $key => $value) {
                    $table->addCell(NULL, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Referencia<w:br/>(VLE-PPT)<w:br/>ppm', $encabezado_texto3);
                    $table->addCell(NULL, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Resultado<w:br/>(Concentración)<w:br/>ppm', $encabezado_texto3);
                }


                $punto = 'XXXX';
                $subdir = 'XXXX';
                $activo = 'XXXX';
                $instalacion = 'XXXX';
                $area = 'XXXX';
                $nombre = 'XXXX';
                $ficha = 'XXXX';
                $geo = 'XXXX'; //$punto2 = 'XXXX';  $dosimentria = 'XXXX';
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila


                    if ($punto != $value->punto) {
                        $numero_fila += 1;
                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_fila, $texto3);
                        $punto = $value->punto;
                    } else {
                        $table->addCell($ancho_col_1, $continua_fila3);
                    }


                    if ($subdir != $value->catsubdireccion_nombre) {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdir = $value->catsubdireccion_nombre;
                    } else {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if ($activo != $value->gerencia_activo) {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $activo = $value->gerencia_activo;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if ($instalacion != $value->instalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->instalacion, $texto3);
                        $instalacion = $value->instalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->area) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->area, $texto3);
                        $area = $value->area;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    if ($nombre != $value->nombre) {
                        $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->nombre, $texto3);
                        $nombre = $value->nombre;
                    } else {
                        $table->addCell($ancho_col_6, $continua_fila3);
                    }


                    if ($ficha != $value->ficha) {
                        $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->ficha, $texto3);
                        $ficha = $value->ficha;
                    } else {
                        $table->addCell($ancho_col_7, $continua_fila3);
                    }


                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->categoria, $texto3);
                    $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($value->total, $texto3);
                    $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->geo, $texto3);


                    foreach ($parametros as $key2 => $parametro) {
                        $agentes = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.punto,
                                                    TABLA.parametro,
                                                    REPLACE(REPLACE(TABLA.concentracion, "<", "˂"), ">", "˃") AS concentracion,
                                                    REPLACE(REPLACE(TABLA.limite, "<", "˂"), ">", "˃") AS limite
                                                    -- TABLA.concentracion,
                                                    -- TABLA.limite
                                                FROM
                                                    (
                                                        SELECT
                                                            reportequimicosevaluacion.proyecto_id,
                                                            reportequimicosevaluacion.registro_id,
                                                            reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion AS concentracion,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS limite
                                                        FROM
                                                            reportequimicosevaluacionparametro
                                                            LEFT JOIN reportequimicosevaluacion ON reportequimicosevaluacionparametro.reportequimicosevaluacion_id = reportequimicosevaluacion.id
                                                        WHERE
                                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                            AND reportequimicosevaluacion.reportequimicosevaluacion_punto = ' . $value->punto . ' 
                                                    ) AS TABLA                                                
                                                WHERE
                                                    TABLA.parametro = "' . $parametro->parametro . '"
                                                    -- TABLA.parametro = "Ácido sulfhídrico"
                                                    -- TABLA.parametro = "Metano"
                                                    -- OR TABLA.parametro = "Etano"
                                                    -- OR TABLA.parametro = "Propano"
                                                    -- OR TABLA.parametro = "Butano"
                                                    -- OR TABLA.parametro = "Pentano"
                                                ORDER BY
                                                    TABLA.parametro ASC
                                                LIMIT 1');

                        if (count($agentes) > 0) {
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText($agentes[0]->limite, $texto3);
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText($agentes[0]->concentracion, $texto3);
                        } else {
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText('', $texto3);
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText('', $texto3);
                        }
                    }
                }
            } else {
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 500;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 1500;
                $ancho_col_6 = 2000;
                $ancho_col_7 = 1000;
                $ancho_col_8 = 2000;
                $ancho_col_9 = 1500;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(($ancho_col_2 + $ancho_col_3 + $ancho_col_4 + $ancho_col_5), array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(($ancho_col_6 + $ancho_col_7 + $ancho_col_8), array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                $table->addCell($ancho_col_9, array('gridSpan' => count($parametros), 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes químicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(($ancho_col_2 + $ancho_col_3 + $ancho_col_4 + $ancho_col_5), array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(($ancho_col_6 + $ancho_col_7 + $ancho_col_8), array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                $table->addCell($ancho_col_9, array('gridSpan' => count($parametros), 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('COV´S<w:br/>(Medición/VLE)', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Gerencia o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Área de referencia en atlas de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);

                foreach ($parametros as $key => $value) {
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText($value->parametro, $encabezado_texto3);
                }


                $punto = 'XXXX';
                $subdir = 'XXXX';
                $activo = 'XXXX';
                $instalacion = 'XXXX';
                $area = 'XXXX';
                $nombre = 'XXXX';
                $ficha = 'XXXX';
                $punto2 = 'XXXX';
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila


                    if ($punto != $value->punto) {
                        $numero_fila += 1;

                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_fila, $texto3);
                        $punto = $value->punto;
                    } else {
                        $table->addCell($ancho_col_1, $continua_fila3);
                    }


                    if ($subdir != $value->catsubdireccion_nombre) {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdir = $value->catsubdireccion_nombre;
                    } else {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if ($activo != $value->gerencia_activo) {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $activo = $value->gerencia_activo;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if ($instalacion != $value->instalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->instalacion, $texto3);
                        $instalacion = $value->instalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->area) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->area, $texto3);
                        $area = $value->area;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    if ($nombre != $value->nombre) {
                        $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->nombre, $texto3);
                        $nombre = $value->nombre;
                    } else {
                        $table->addCell($ancho_col_6, $continua_fila3);
                    }


                    if ($ficha != $value->ficha) {
                        $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->ficha, $texto3);
                        $ficha = $value->ficha;
                    } else {
                        $table->addCell($ancho_col_7, $continua_fila3);
                    }


                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->categoria, $texto3);


                    foreach ($parametros as $key2 => $parametro) {
                        $agentes = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.punto,
                                                    TABLA.parametro,
                                                    REPLACE(REPLACE(TABLA.concentracion, "<", "˂"), ">", "˃") AS concentracion,
                                                    REPLACE(REPLACE(TABLA.limite, "<", "˂"), ">", "˃") AS limite
                                                    -- TABLA.concentracion,
                                                    -- TABLA.limite
                                                FROM
                                                    (
                                                        SELECT
                                                            reportequimicosevaluacion.proyecto_id,
                                                            reportequimicosevaluacion.registro_id,
                                                            reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion AS concentracion,
                                                            reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS limite
                                                        FROM
                                                            reportequimicosevaluacionparametro
                                                            LEFT JOIN reportequimicosevaluacion ON reportequimicosevaluacionparametro.reportequimicosevaluacion_id = reportequimicosevaluacion.id
                                                        WHERE
                                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                            AND reportequimicosevaluacion.reportequimicosevaluacion_punto = ' . $value->punto . ' 
                                                    ) AS TABLA
                                                WHERE
                                                    TABLA.parametro = "' . $parametro->parametro . '"
                                                    -- TABLA.parametro = "Ácido sulfhídrico"
                                                    -- TABLA.parametro = "Metano"
                                                    -- OR TABLA.parametro = "Etano"
                                                    -- OR TABLA.parametro = "Propano"
                                                    -- OR TABLA.parametro = "Butano"
                                                    -- OR TABLA.parametro = "Pentano"
                                                ORDER BY
                                                        TABLA.parametro ASC');


                        if (count($agentes) > 0) {
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText($agentes[0]->concentracion . ' / ' . $agentes[0]->limite, $texto3);
                        } else {
                            $table->addCell(NULL, $celda3)->addTextRun($centrado3)->addText('', $texto3);
                        }
                    }
                }
            }


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


            $plantillaword->setComplexBlock('TABLA_7_2', $table);


            // CONCLUSION
            //================================================================================


            $where_conclusion = '';
            if (($partida_id + 0) > 0) {
                $where_conclusion = 'AND reportequimicosconclusion.catreportequimicospartidas_id = ' . $partida_id;
            }


            $sql = DB::select('SELECT
                                    reportequimicosconclusion.proyecto_id,
                                    reportequimicosconclusion.registro_id,
                                    reportequimicosconclusion.catreportequimicospartidas_id,
                                    -- reportequimicosconclusion.reportequimicosconclusion_conclusion 
                                    REPLACE(REPLACE(REPLACE(reportequimicosconclusion.reportequimicosconclusion_conclusion, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reportequimicosconclusion_conclusion
                                FROM
                                    reportequimicosconclusion
                                WHERE
                                    reportequimicosconclusion.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportequimicosconclusion.registro_id = ' . $reporteregistro_id . '');


            if (count($sql) > 0) {
                if (count($sql) == 1) {
                    $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $sql[0]->reportequimicosconclusion_conclusion));
                } else {
                    $conclusiones = '';
                    foreach ($sql as $key => $value) {
                        $conclusiones .= ($key + 1) . '.- ' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $value->reportequimicosconclusion_conclusion) . '<w:br/><w:br/><w:br/>';
                    }

                    $plantillaword->setValue('CONCLUSION', $conclusiones);
                }
            } else {
                $plantillaword->setValue('CONCLUSION', 'NO SE ENCONTRÓ CONCLUSIÓN PARA ESTE INFORME O PARTIDA.');
            }


            // DASHBOARD
            //================================================================================


            // $dashboard_ruta = storage_path('app/reportes/proyecto/'.$proyecto_id.'/'.$agente_nombre.'/'.$reporteregistro_id.'/partidas/grafica_partida_'.$partida_id.'.jpg');


            // if (file_exists($dashboard_ruta))
            // {
            //     $plantillaword->setImageValue('DASHBOARD', array('path' => $dashboard_ruta, 'height' => 515, 'width' => 860, 'ratio' => false, 'borderColor' => '000000'));
            // }
            // else
            // {
            //     $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
            // }


            //--------------------------------


            if ($request->grafica_dashboard) {
                $imagen_base64 = base64_decode(str_replace("data:image/jpeg;base64,", "", $request->grafica_dashboard));
                // $imagen_temporal_ruta = 'reportes/informes/dashboard_'.$agente_nombre.$partida_id.'_'.$proyecto->proyecto_folio.'.jpg';
                $imagen_temporal_ruta = 'reportes/informes/dashboard_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.jpg';
                Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage


                if (Storage::exists($imagen_temporal_ruta)) {
                    $plantillaword->setImageValue('DASHBOARD', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'height' => 500, 'width' => 860, 'ratio' => false, 'borderColor' => '000000'));


                    Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                } else {
                    $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
                }
            } else {
                $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
            }



            // RECOMENDACIONES
            //================================================================================


            $sql = DB::select('SELECT
                                    reporterecomendaciones.proyecto_id,
                                    reporterecomendaciones.registro_id,
                                    reporterecomendaciones.agente_nombre,
                                    reporterecomendaciones.reporterecomendacionescatalogo_id,
                                    reporterecomendaciones.reporterecomendaciones_tipo,
                                    -- reporterecomendaciones.reporterecomendaciones_descripcion,
                                    REPLACE(REPLACE(REPLACE(reporterecomendaciones.reporterecomendaciones_descripcion, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS reporterecomendaciones_descripcion,
                                    IFNULL(reporterecomendaciones.catalogo_id, 0) AS catquimicospartida_id
                                FROM
                                    reporterecomendaciones
                                WHERE
                                    reporterecomendaciones.proyecto_id = ' . $proyecto_id . ' 
                                    AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
                                    AND reporterecomendaciones.agente_nombre = "' . $agente_nombre . '" 
                                ORDER BY
                                    reporterecomendaciones.reporterecomendaciones_tipo ASC');


            $recomendacion = '';
            foreach ($sql as $key => $value) {
                if (($key + 0) < (count($sql) - 1)) {
                    $recomendacion .= $value->reporterecomendaciones_descripcion . '\n\n';
                } else {
                    $recomendacion .= $value->reporterecomendaciones_descripcion;
                }
            }


            $plantillaword->setValue('RECOMENDACIONES', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos, $recomendacion));


            // RESPONSABLES
            //================================================================================


            // RESPONSABLE 1, FOTO DOCUMENTO
            if ($reporte->reportequimicos_responsable1documento) {
                if (file_exists(storage_path('app/' . $reporte->reportequimicos_responsable1documento))) {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportequimicos_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE1', $reporte->reportequimicos_responsable1 . "<w:br/>" . $reporte->reportequimicos_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporte->reportequimicos_responsable2documento) {
                if (file_exists(storage_path('app/' . $reporte->reportequimicos_responsable2documento))) {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportequimicos_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE2', $reporte->reportequimicos_responsable2 . "<w:br/>" . $reporte->reportequimicos_responsable2cargo);


            // TABLA ANEXO 1, Memoria fotográfica  - CREAR VARIABLES
            //================================================================================


            $where_condicion = '';
            if (($partida_id + 0) > 0) {
                $where_condicion = 'AND proyectoevidenciafoto.proyectoevidenciafoto_nopunto = ' . $partida_id;
            }


            $fotos = DB::select('SELECT
                                    proyectoevidenciafoto.id,
                                    proyectoevidenciafoto.proyecto_id,
                                    proyectoevidenciafoto.proveedor_id,
                                    proyectoevidenciafoto.agente_id,
                                    proyectoevidenciafoto.agente_nombre,
                                    proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto AS catquimicospartidas_id,
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
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Evaluación de agentes químicos', $encabezado_texto);


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


            $plantillaword->setComplexBlock('TABLA_MEMORIA_FOTOGRAFICA', $table);


            // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - CREAR VARIABLES
            //================================================================================


            $where_condicion = '';
            if (($partida_id + 0) > 0) {
                $where_condicion = 'WHERE (TABLA.catreportequimicospartidas_id = "" AND )';
            }


            $planoscarpetas = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.agente_nombre,
                                                TABLA.catreportequimicospartidas_id,
                                                TABLA.reporteplanoscarpetas_nombre
                                            FROM
                                                (
                                                    SELECT
                                                        reporteplanoscarpetas.id,
                                                        reporteplanoscarpetas.proyecto_id,
                                                        reporteplanoscarpetas.agente_id,
                                                        reporteplanoscarpetas.agente_nombre,
                                                        reporteplanoscarpetas.reporteplanoscarpetas_nombre,
                                                        IFNULL((
                                                            SELECT
                                                                -- proyectoevidenciaplano.proyecto_id,
                                                                -- proyectoevidenciaplano.agente_nombre,
                                                                -- proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                                                proyectoevidenciaplano.catreportequimicospartidas_id 
                                                            FROM
                                                                proyectoevidenciaplano
                                                            WHERE
                                                                proyectoevidenciaplano.proyecto_id = reporteplanoscarpetas.proyecto_id 
                                                                AND proyectoevidenciaplano.agente_nombre = reporteplanoscarpetas.agente_nombre 
                                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = reporteplanoscarpetas.reporteplanoscarpetas_nombre 
                                                            LIMIT   1
                                                        ), 0) AS catreportequimicospartidas_id
                                                    FROM
                                                        reporteplanoscarpetas
                                                    WHERE
                                                        reporteplanoscarpetas.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteplanoscarpetas.registro_id = ' . $reporteregistro_id . ' 
                                                        AND reporteplanoscarpetas.agente_nombre = "' . $agente_nombre . '"
                                                ) AS TABLA
                                            -- WHERE
                                                -- TABLA.catreportequimicospartidas_id = 3
                                            ORDER BY
                                                TABLA.reporteplanoscarpetas_nombre ASC');


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


            // TABLA ANEXO 3, DATOS DE LOS CONTAMINANTES EVALUADOS
            //================================================================================

            $sql = DB::select('CALL sp_anexo3_11_3_informe_quimico_b(?)', [$proyecto_id]);

            $ancho_col_1 = 1500;
            $ancho_col_2 = 1500;
            // $ancho_col_3 = 1600;
            $ancho_col_4 = 2850;
            // $ancho_col_5 = 1250;
            $ancho_col_6 = 2200;
            $ancho_col_7 = 2200;
            $ancho_col_8 = 2750;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Nombre', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('No. de CAS', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Peso molecular<w:br/>gr/mol', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Vías de ingreso<w:br/>al organismo', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación  de riesgo<w:br/>a la salud', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Valor Límite<w:br/>de Exposición<w:br/>(VLE)', $encabezado_texto);

            $numero_fila = 0;
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila

                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->NOMBRE, $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->NUM_CAS, $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->PM, $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->INGRESO, $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->RIESGO, $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText($value->VLE, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_CONTAMINANTES_EVALUADOS', $table);


            // TABLA ANEXO 4, EQUIPO UTILIZADO PARA LA MEDICION
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $sql = DB::select('SELECT DISTINCT
                                    reporteequiposutilizados.proyecto_id,
                                    reporteequiposutilizados.registro_id,
                                    reporteequiposutilizados.agente_nombre,
                                    equipo.proveedor_id,
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
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_de_' . $agente_nombre . '_proyecto_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

            // sleep(1);

            // ABRIR NUEVA PLANTILLA
            $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_de_' . $agente_nombre . '_proyecto_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


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

                    $plantillaword->setValue('PUNTO_' . $i . '_DESCRIPCION', $fotos[$i]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 1) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 1) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 1)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 1) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_' . ($i + 1) . '_DESCRIPCION', $fotos[($i + 1)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 2) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 2) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 2)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 2) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_' . ($i + 2) . '_DESCRIPCION', $fotos[($i + 2)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 3) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 3) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 3)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 3) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

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


            $where_anexos = '';
            if (($proveedor_id + 0) > 0) {
                $where_anexos = ' AND TABLA2.proveedor_id = ' . $proveedor_id;
            }


            $anexos_lista = DB::select('SELECT
                                        REPLACE(ANEXO.nombre, "/", "-") AS nombre,
                                        ANEXO.archivo
                                    FROM
                                        (
                                            (
                                                SELECT DISTINCT
                                                    CONCAT("Certificado equipo ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                                    equipos_documentos.RUTA_DOCUMENTO AS archivo
                                                FROM
                                                    reporteequiposutilizados
                                                LEFT JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                                LEFT JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
                                                WHERE
                                                    reporteequiposutilizados.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . '
                                                    AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                    AND equipos_documentos.DOCUMENTO_TIPO = 4
                                                    AND IFNULL(equipos_documentos.RUTA_DOCUMENTO, "") != ""
                                            )
                                            UNION ALL
                                            (
                                                SELECT
                                                    CONCAT("Carta vigencia - ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                                    equipos_documentos.RUTA_DOCUMENTO AS archivo
                                                FROM
                                                    reporteequiposutilizados
                                                LEFT JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                                LEFT JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
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
                                                    CONCAT(TABLA2.acreditacion_Entidad, " ",TABLA2.acreditacion_Numero) AS nombre,
                                                    TABLA2.acreditacion_SoportePDF AS archivo
                                                FROM
                                                    (
                                                        SELECT
                                                            TABLA.proyecto_id,
                                                            TABLA.proveedor_id,
                                                            TABLA.proveedor_NombreComercial,
                                                            acreditacion.id,
                                                            acreditacion.acreditacion_Entidad,
                                                            acreditacion.acreditacion_Numero,
                                                            IF(acreditacion.acreditacion_Tipo = 1, "Acreditación", "Aprobación") AS acreditacion_Tipo,
                                                            cat_area.catArea_Nombre,
                                                            acreditacion.acreditacion_Expedicion,
                                                            acreditacion.acreditacion_Vigencia,
                                                            IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) AS vigencia_dias,
                                                            IF(acreditacion.acreditacion_Vigencia, CONCAT(acreditacion.acreditacion_Vigencia, " (", (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1)," d)"), "N/A") AS vigencia_texto,
                                                            (
                                                                CASE
                                                                    WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) = 0 THEN ""
                                                                    WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) >= 90 THEN ""
                                                                    WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) >= 30 THEN "text-warning"
                                                                    ELSE "text-danger"
                                                                END
                                                            ) AS vigencia_color,
                                                            acreditacion.acreditacion_SoportePDF,
                                                            IFNULL((
                                                                SELECT  
                                                                    IF(IFNULL(reporteanexos.reporteanexos_rutaanexo, "") = "", "", "checked")
                                                                FROM
                                                                    reporteanexos
                                                                WHERE
                                                                    reporteanexos.proyecto_id = TABLA.proyecto_id
                                                                    AND reporteanexos.reporteanexos_tipo = 2
                                                                    AND reporteanexos.reporteanexos_rutaanexo = acreditacion.acreditacion_SoportePDF
                                                                LIMIT 1
                                                            ), "") AS checked 
                                                        FROM
                                                            (
                                                                SELECT
                                                                    proyectoproveedores.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proveedor.proveedor_NombreComercial
                                                                FROM
                                                                    proyectoproveedores
                                                                    LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                                                WHERE
                                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                                    AND proyectoproveedores.catprueba_id = 15
                                                                GROUP BY
                                                                    proyectoproveedores.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proveedor.proveedor_NombreComercial
                                                            ) AS TABLA
                                                            LEFT JOIN acreditacion ON TABLA.proveedor_id = acreditacion.proveedor_id
                                                            LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                                        ORDER BY
                                                            TABLA.proveedor_NombreComercial ASC,
                                                            acreditacion.acreditacion_Entidad ASC
                                                    ) AS TABLA2
                                                WHERE
                                                    TABLA2.checked = "checked"
                                                    ' . $where_anexos . '
                                                ORDER BY
                                                    TABLA2.acreditacion_Entidad ASC
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
                                                    AND reporteanexos.reporteanexos_tipo = 1
                                            )
                                        ) AS ANEXO');



            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL


            // $partida = clientepartidasModel::findOrFail($partida_id);
            // $pda = explode(' ', $partida->clientepartidas_descripcion);
            $informe_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';

            // GUARDAR WORD FINAL
            $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word


            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_de_' . $agente_nombre . '_proyecto_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                Storage::delete('reportes/informes/Informe_de_' . $agente_nombre . '_proyecto_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
            }



            if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
            {
                //================================================================================
                // CREAR .ZIP INFORME

                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');

                // Zip File Name
                $zip_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';

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
                $zip_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';

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
                    'reporterevisionesarchivo_tipo' => $request->partida_id,
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
                                                AND reporterevisiones.agente_id = 15 -- Químicos 
                                            ORDER BY
                                                reporterevisiones.reporterevisiones_revision DESC');


                // CREAR NUEVA REVISION
                // -------------------------------------------------


                if (($revisiones[0]->id + 0) == ($request->ultimarevision_id + 0)) {
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
                }


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


    public function reportequimicosworddescargar($proyecto_id, $revision_id, $partida_id, $ultima_revision)
    {
        $agente_nombre = 'Químicos';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            // $partida = clientepartidasModel::findOrFail($partida_id);
            // $pda = explode(' ', $partida->clientepartidas_descripcion);

            $zip_nombre = 'Informe de Químicos - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';



            if (Storage::exists('reportes/informes/' . $zip_nombre)) {
                return response()->download(storage_path('app/reportes/informes/' . $zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
            } else {
                return '<h3>No se encontró el informe  de - ' . $agente_nombre . ' , intentelo de nuevo</h3>';
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
                                                reporterevisiones.id = ?', [$revision_id]);


            if (count($archivo_historial) > 0) {
                if (Storage::exists($archivo_historial[0]->reporterevisionesarchivo_archivo)) {
                    return response()->download(storage_path('app/' . $archivo_historial[0]->reporterevisionesarchivo_archivo), "", array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(false);
                } else {
                    return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . '</h3>';
                }
            } else {
                return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . '</h3>';
            }
        }
    }
}
