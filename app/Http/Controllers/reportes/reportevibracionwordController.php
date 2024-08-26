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
use App\modelos\reportes\reportevibracioncatalogoModel;
use App\modelos\reportes\reportevibracionModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportevibracionareacategoriaModel;
use App\modelos\reportes\reportevibracionareamaquinariaModel;
use App\modelos\reportes\reportevibracionevaluacionModel;
use App\modelos\reportes\reportevibracionevaluaciondatosModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;


class reportevibracionwordController extends Controller
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
    public function reportevibracionword(Request $request)
    {
        try {
            // dd($request->all());


            $proyecto_id = $request->proyecto_id;
            $reporteregistro_id = $request->reporteregistro_id;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL, "es_MX");

            ################ DATOS GENERALES ######################
            $agente_id = 2;
            $agente_nombre = "Vibración";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);


            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reportevibracionModel::where('proyecto_id', $proyecto_id)->get();
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
                $reporte  = reportevibracionModel::findOrFail($reporteregistro_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe llenar los datos para poder generarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_vibracion_sin_MEL.docx')); //Ruta carpeta storage
            } else {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_vibracion_sin_MEL.docx')); //Ruta carpeta storage
            }


            ################ PORTADA EXTERNA ####################
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 2) // Vibracion
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

            $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportevibracion_fecha;
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
                $cancelado_texto = '<w:br/>INFORME REVISIÓN ' . $revision->reporterevisiones_revision . ' CANCELADO';
            }
            if (($revision->reporterevisiones_revision + 0) > 0) {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
            } else {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
            }





            ##### INTRODUCCION ###################

            $introduccionTexto = $agente[0]->reportevibracion_introduccion;
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


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_objetivoespecifico));


            // METODOLOGIA
            //================================================================================


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_metodologia_4_1));


            //4.2
            //--------------------------------


            if (($reporte->reportevibracion_alcanceinforme + 0) == 1 || ($reporte->reportevibracion_alcanceinforme + 0) == 3) {
                $texto = '<w:br/>
                            <w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Para la evaluación de vibraciones se realizó en cada ciclo de exposición la medición correspondiente al POE usando los procedimientos de evaluación para cuerpo entero.') . '</w:t>
                            </w:p><w:br/>
                            <w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Para la evaluación en cuerpo entero en cada punto de medición, se localizan tres ejes ortogonales de acuerdo con la figura 1 (NOM-024-STPS-2001), en los que se realizan las mediciones continuas de la aceleración y se registran al menos durante un minuto en cada una de las bandas de tercios de octava.') . '</w:t>
                            </w:p>';

                $plantillaword->setValue('4_2_CUERPO_PARRAFO1', $texto);

                $plantillaword->setImageValue('4_2_CUERPO_FIGURA1', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.2_1.jpg'), 'width' => 380, 'height' => 400, 'ratio' => true));

                $plantillaword->setValue('4_2_CUERPO_PARRAFO2', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 1</w:t></w:r><w:r><w:t>. Direcciones de incidencia de las vibraciones sobre el cuerpo humano (NOM-024-STPS-2001).<w:br/>');

                $plantillaword->setValue('4_2_CUERPO_PARRAFO3', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>ax, ay, az</w:t></w:r><w:r><w:t>: Son las direcciones de la aceleración en los ejes x, y, z.<w:br/>
                                                                </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>eje x</w:t></w:r><w:r><w:t>: Es la dirección de espalda a pecho.<w:br/>
                                                                </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>eje y</w:t></w:r><w:r><w:t>: Es la dirección de lado derecho a izquierdo.<w:br/>
                                                                </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>eje z</w:t></w:r><w:r><w:t>: Es la dirección de los pies o parte inferior, a la cabeza.<w:br/>');

                $plantillaword->setValue('4_2_CUERPO_PARRAFO4', 'La evaluación se realizó de acuerdo con el procedimiento establecido en el punto 8.3.2.1. Procedimiento de evaluación de vibraciones para cuerpo entero de la NOM-024-STPS-2001 “Vibraciones-Condiciones de seguridad e higiene en los centros de trabajo”.');
            }


            if (($reporte->reportevibracion_alcanceinforme + 0) == 2 || ($reporte->reportevibracion_alcanceinforme + 0) == 3) {
                $texto = '<w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Para la evaluación en extremidades superiores (mano-brazo) en cada punto de medición, se localizan tres ejes ortogonales, cercanos al punto de contacto de las vibraciones con la mano, de acuerdo con lo mostrado por los sistemas de coordenadas biodinámicas y basicéntricas de la figura 2, en los que se realizan las mediciones continuas de la aceleración y se registran al menos durante un minuto, en cada una de las bandas de tercios de octava.') . '</w:t>
                            </w:p>';

                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO1', $texto);

                $plantillaword->setImageValue('4_2_EXTREMIDADES_FIGURA1', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.2_2.jpg'), 'width' => 380, 'height' => 240, 'ratio' => true));

                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO2', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 2</w:t></w:r><w:r><w:t>. Sistemas biodinámico y basicéntrico de coordenadas (NOM-024-STPS-2001).<w:br/>');

                $texto = '<w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Se realiza un análisis espectral en bandas de tercios de octava (de 8 a 1600 Hz) por cada eje y se calculó el componente direccional de la aceleración ponderada conforme a la siguiente ecuación:') . '</w:t>
                            </w:p>';

                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO3', $texto);

                $plantillaword->setImageValue('4_2_EXTREMIDADES_FIGURA2', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.2_3.jpg'), 'width' => 380, 'height' => 100, 'ratio' => true));

                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO4', 'Dónde:<w:br/><w:br/>
                                                                        </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>ak</w:t></w:r><w:r><w:t>: Es el componente direccional de la aceleración ponderada;<w:br/>
                                                                        </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>T</w:t></w:r><w:r><w:t>: Es la duración de la exposición diaria;<w:br/>
                                                                        </w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Kj</w:t></w:r><w:r><w:t>: Es la iésima frecuencia ponderada, valor cuadrático medio de la componente de la aceleración con duración Ti.');
            }


            //4.3
            //--------------------------------


            if (($reporte->reportevibracion_alcanceinforme + 0) == 1 || ($reporte->reportevibracion_alcanceinforme + 0) == 3) {
                $texto = '<w:p>
                                <w:rPr>
                                    <w:jc w:val="left"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="true"/>
                                </w:rPr>
                                <w:t>
                                    <w:br/>En cuerpo entero
                                </w:t><w:br/>
                            </w:p>
                            <w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="false"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Cuando se conoce la frecuencia de un mecanismo que genera vibración y se relaciona con la aceleración en m/s² ya sea en el eje de aceleración longitudinal az, o en los ejes de aceleración transversal ax y ay, se obtiene el tiempo de exposición que puede variar de un minuto a veinticuatro horas. Los límites de exposición a vibraciones en el eje longitudinal az y en los ejes transversales ax y ay, se establecen en las Tablas 1 y 2 de la norma, respectivamente.') . '</w:t>
                            </w:p>';

                $plantillaword->setValue('4_3_CUERPO_PARRAFO1', $texto);

                $plantillaword->setImageValue('4_3_CUERPO_FIGURA1', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.3_1.jpg'), 'width' => 580, 'height' => 620, 'ratio' => false));

                $plantillaword->setImageValue('4_3_CUERPO_FIGURA2', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.3_2.jpg'), 'width' => 580, 'height' => 250, 'ratio' => false));

                $plantillaword->setImageValue('4_3_CUERPO_FIGURA3', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.3_3.jpg'), 'width' => 580, 'height' => 500, 'ratio' => false));

                $plantillaword->setImageValue('4_3_CUERPO_FIGURA4', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.3_4.jpg'), 'width' => 580, 'height' => 300, 'ratio' => false));
            }


            if (($reporte->reportevibracion_alcanceinforme + 0) == 2 || ($reporte->reportevibracion_alcanceinforme + 0) == 3) {
                $texto = '<w:p>
                                <w:rPr>
                                    <w:jc w:val="left"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="true"/>
                                </w:rPr>
                                <w:br/>
                                    <w:t>
                                        En extremidades superiores
                                    </w:t>
                                <w:br/>
                            </w:p>
                            <w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="false"/>
                                </w:pPr>
                                <w:t>' . htmlspecialchars('Dependiendo del tiempo de exposición, se establecen los valores permitidos de aceleración ponderada (que se deben calcular según se establece en los Apartados 8.3.2.2.1 al 8.3.2.2.6, y en la Tabla 3).') . '</w:t>
                            </w:p>';

                $plantillaword->setValue('4_3_EXTREMIDADES_PARRAFO1', $texto);

                $plantillaword->setImageValue('4_3_EXTREMIDADES_FIGURA1', array('path' => storage_path('assets/images/reportes/reportevibracion_fig_4.3_5.jpg'), 'width' => 580, 'height' => 240, 'ratio' => false));

                $plantillaword->setValue('4_3_EXTREMIDADES_PARRAFO2', '<w:br/></w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>(*) Nota</w:t></w:r><w:r><w:t>: Comúnmente, uno de los ejes de vibración domina sobre los dos restantes. Si uno o más ejes de vibración sobrepasan la exposición total diaria, se han sobrepasado los valores de los límites máximos de exposición.');
            }


            //--------------------------------


            if (($reporte->reportevibracion_alcanceinforme + 0) == 1) {
                $plantillaword->setValue('4_2_PARRAFO_ADICIONAL', '<w:br/>En ' . $reporte->reportevibracion_instalacion . ' se realizó la metodología de cuerpo entero y de acuerdo al reconocimiento inicial realizado, para las mediciones de extremidades superiores (mano-brazo) no se encontraron puestos de trabajo que tengan este tipo de exposición, por lo que esta metodología no será aplicada.');

                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO1', '');
                $plantillaword->setValue('4_2_EXTREMIDADES_FIGURA1', '');
                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO2', '');
                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO3', '');
                $plantillaword->setValue('4_2_EXTREMIDADES_FIGURA2', '');
                $plantillaword->setValue('4_2_EXTREMIDADES_PARRAFO4', '');


                $plantillaword->setValue('4_3_CUERPO_FIGURA2_PIE', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 2</w:t></w:r><w:r><w:t>. Límites de aceleración longitudinal (az) como función de la frecuencia y del tiempo de exposición (NOM-024-STPS-2001)');
                $plantillaword->setValue('4_3_CUERPO_FIGURA4_PIE', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 3</w:t></w:r><w:r><w:t>. Límites de aceleración transversal (ax, ay) como función de la frecuencia y del tiempo de exposición (NOM-024-STPS-2001)');


                $plantillaword->setValue('4_3_EXTREMIDADES_PARRAFO1', '');
                $plantillaword->setValue('4_3_EXTREMIDADES_FIGURA1', '');
                $plantillaword->setValue('4_3_EXTREMIDADES_PARRAFO2', '');
            } else if (($reporte->reportevibracion_alcanceinforme + 0) == 2) {
                $plantillaword->setValue('4_2_PARRAFO_ADICIONAL', '');

                $plantillaword->setValue('4_2_CUERPO_PARRAFO1', '');
                $plantillaword->setValue('4_2_CUERPO_FIGURA1', '');
                $plantillaword->setValue('4_2_CUERPO_PARRAFO2', '');
                $plantillaword->setValue('4_2_CUERPO_PARRAFO3', '');
                $plantillaword->setValue('4_2_CUERPO_PARRAFO4', '');


                $plantillaword->setValue('4_3_CUERPO_PARRAFO1', '');
                $plantillaword->setValue('4_3_CUERPO_FIGURA1', '');
                $plantillaword->setValue('4_3_CUERPO_FIGURA2', '');
                $plantillaword->setValue('4_3_CUERPO_FIGURA2_PIE', '');
                $plantillaword->setValue('4_3_CUERPO_FIGURA3', '');
                $plantillaword->setValue('4_3_CUERPO_FIGURA4', '');
            } else {
                $plantillaword->setValue('4_3_CUERPO_FIGURA2_PIE', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 3</w:t></w:r><w:r><w:t>. Límites de aceleración longitudinal (az) como función de la frecuencia y del tiempo de exposición (NOM-024-STPS-2001)');
                $plantillaword->setValue('4_3_CUERPO_FIGURA4_PIE', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Figura 4</w:t></w:r><w:r><w:t>. Límites de aceleración transversal (ax, ay) como función de la frecuencia y del tiempo de exposición (NOM-024-STPS-2001)');
                $plantillaword->setValue('4_2_PARRAFO_ADICIONAL', '');
            }


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_ubicacioninstalacion));


            // Imagen FOTO
            if ($reporte->reportevibracion_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reporte->reportevibracion_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporte->reportevibracion_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            // PROCESO INSTALACION
            //================================================================================


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $reporte->reportevibracion_procesoinstalacion))));


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


            $sql = DB::select('SELECT
                                    reportearea.proyecto_id,
                                    reportearea.id,
                                    REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reportearea_instalacion,
                                    reportearea.reportearea_nombre,
                                    reportearea.reportearea_orden,
                                    reportearea.reportearea_porcientooperacion,
                                    reportearea.reportevibracionarea_porcientooperacion,
                                    reportearea.reportearea_caracteristicaarea,
                                    reportearea.reportearea_tipoventilacion,
                                    reporteareacategoria.reportecategoria_id,
                                    reportecategoria.reportecategoria_orden,
                                    reportecategoria.reportecategoria_nombre,
                                    IFNULL((
                                        SELECT
                                            IF(reportevibracionareacategoria.reportecategoria_id, "activo", "") AS checked
                                        FROM
                                            reportevibracionareacategoria
                                        WHERE
                                            reportevibracionareacategoria.reportearea_id = reportearea.id
                                            AND reportevibracionareacategoria.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                        LIMIT 1
                                    ), "") AS activo,
                                    reporteareacategoria.reporteareacategoria_total,
                                    reporteareacategoria.reporteareacategoria_geh,
                                    reporteareacategoria.reporteareacategoria_actividades,
                                    (
                                        SELECT
                                            reportevibracion.reportevibracion_actividadprincipal 
                                        FROM
                                            reportevibracion
                                        WHERE
                                            reportevibracion.proyecto_id = reportearea.proyecto_id
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


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    if (($key + 0) != 0) {
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
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reportearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                if ($area != $value->reportearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_total, $texto);


                if ($instalacion != $value->reportearea_instalacion) {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->actividad_principal_instalacion), $texto);

                    $instalacion = $value->reportearea_instalacion;
                } else {
                    $table->addCell($ancho_col_6, $continua_fila);
                }
            }


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


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
            $table->addCell($ancho_col_5, $continua_fila);


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($justificado)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);


            $plantillaword->setComplexBlock('TABLA_5_3', $table);


            // TABLA 5.4 Actividades del personal expuesto
            //================================================================================


            $sql = DB::select('SELECT
                                    TABLA.proyecto_id,
                                    TABLA.id,
                                    TABLA.reportearea_instalacion,
                                    TABLA.reportearea_nombre,
                                    TABLA.reportearea_orden,
                                    TABLA.reportevibracionarea_porcientooperacion,
                                    TABLA.reportecategoria_id,
                                    TABLA.reportecategoria_orden,
                                    TABLA.reportecategoria_nombre,
                                    TABLA.activo,
                                    TABLA.reporteareacategoria_total,
                                    TABLA.reporteareacategoria_geh,
                                    TABLA.reporteareacategoria_actividades,
                                    TABLA.actividad_principal_instalacion
                                FROM
                                    (
                                        SELECT
                                            reportearea.proyecto_id,
                                            reportearea.id,
                                            reportearea.reportearea_instalacion,
                                            reportearea.reportearea_nombre,
                                            reportearea.reportearea_orden,
                                            reportearea.reportearea_porcientooperacion,
                                            reportearea.reportevibracionarea_porcientooperacion,
                                            reporteareacategoria.reportecategoria_id,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre,
                                            IFNULL((
                                                SELECT
                                                    IF(reportevibracionareacategoria.reportecategoria_id, "activo", "") AS checked
                                                FROM
                                                    reportevibracionareacategoria
                                                WHERE
                                                    reportevibracionareacategoria.reportearea_id = reportearea.id
                                                    AND reportevibracionareacategoria.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                                LIMIT 1
                                            ), "") AS activo,
                                            reporteareacategoria.reporteareacategoria_total,
                                            reporteareacategoria.reporteareacategoria_geh,
                                            reporteareacategoria.reporteareacategoria_actividades,
                                            (
                                                SELECT
                                                    reportevibracion.reportevibracion_actividadprincipal 
                                                FROM
                                                    reportevibracion
                                                WHERE
                                                    reportevibracion.proyecto_id = reportearea.proyecto_id
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
                                    TABLA.reportevibracionarea_porcientooperacion > 0
                                    AND TABLA.activo = "activo"
                                ORDER BY
                                    TABLA.reportearea_orden ASC,
                                    TABLA.reportearea_nombre ASC,
                                    TABLA.reportecategoria_orden ASC,
                                    TABLA.reportecategoria_nombre ASC');


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 3000;
            $ancho_col_4 = 4000;
            $ancho_col_5 = 6000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $numero_fila = 0;
            $instalacion = 'xxxx';
            $area = 'xxxx';
            $actividad = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reportearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                if ($area != $value->reportearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteareacategoria_actividades, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_4', $table);


            // TABLA 5.5 Identificación de las áreas
            //================================================================================


            $sql = DB::select('SELECT
                                    reportearea.proyecto_id,
                                    reportevibracionmaquinaria.reportearea_id,
                                    reportearea.reportearea_instalacion,
                                    reportearea.reportearea_nombre,
                                    reportearea.reportearea_orden,
                                    reportearea.reportevibracionarea_porcientooperacion,
                                    reportearea.reportearea_tipoexposicion,
                                    reportevibracionmaquinaria.reportevibracionmaquinaria_nombre,
                                    reportevibracionmaquinaria.reportevibracionmaquinaria_cantidad 
                                FROM
                                    reportevibracionmaquinaria
                                    LEFT JOIN reportearea ON reportevibracionmaquinaria.reportearea_id = reportearea.id
                                WHERE
                                    reportearea.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportearea.reportevibracionarea_porcientooperacion > 0
                                ORDER BY
                                    reportearea.reportearea_orden ASC,
                                    reportearea.reportearea_nombre ASC,
                                    reportevibracionmaquinaria.reportevibracionmaquinaria_nombre ASC');


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 3000;
            $ancho_col_4 = 4000;
            $ancho_col_5 = 1500;
            $ancho_col_6 = 1500;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $numero_fila = 0;
            $instalacion = 'xxxx';
            $area = 'xxxx';
            $tipoexposicion = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Fuente generadora', $encabezado_texto);
                    $table->addCell($ancho_col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Cantidad de fuentes generadoras', $encabezado_texto);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Tipo de exposición', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reportearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);

                    $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportevibracionmaquinaria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reportevibracionmaquinaria_cantidad, $texto);


                if ($tipoexposicion != $value->reportearea_tipoexposicion) {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_tipoexposicion, $texto);

                    $tipoexposicion = $value->reportearea_tipoexposicion;
                } else {
                    $table->addCell($ancho_col_6, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_5_5', $table);


            // TABLA 6.1 Condiciones de operación durante la evaluación (representado en porcentaje)
            //================================================================================


            $sql = DB::select('SELECT
                                    reportearea.proyecto_id,
                                    reportearea.reportearea_instalacion,
                                    reportearea.reportearea_nombre,
                                    reportearea.reportearea_orden,
                                    reportearea.reportevibracionarea_porcientooperacion
                                FROM
                                    reportearea
                                WHERE
                                    reportearea.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportearea.reportevibracionarea_porcientooperacion > 0 
                                ORDER BY
                                    reportearea.reportearea_orden ASC,
                                    reportearea.reportearea_nombre ASC');


            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 5500;
            $ancho_col_4 = 3000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $instalacion = 'xxxx';
            $total = 'XXXX';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reportearea_instalacion;
                    $numero_fila = 0;
                }


                $numero_fila += 1;
                $table->addRow(); //fila


                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);
                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);


                if ($instalacion != $value->reportearea_instalacion || $total != $value->reportevibracionarea_porcientooperacion) {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reportevibracionarea_porcientooperacion . '%', $texto);


                    $instalacion = $value->reportearea_instalacion;
                    $total = $value->reportevibracionarea_porcientooperacion;
                } else {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_6_1', $table);


            // TABLA 6.2 Método de evaluación
            //================================================================================


            $sql = DB::select('SELECT
                                    reportevibracionevaluacion.proyecto_id,
                                    reportevibracionevaluacion.id,
                                    reportevibracionevaluacion.reportearea_id,
                                    REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reportearea_instalacion,
                                    reportearea.reportearea_orden,
                                    reportearea.reportearea_nombre,
                                    reportevibracionevaluacion.reportecategoria_id,
                                    reportecategoria.reportecategoria_orden,
                                    reportecategoria.reportecategoria_nombre,
                                    reportearea.reportearea_tipoexposicion,
                                    IFNULL((
                                        SELECT
                                            -- TABLA.proyecto_id, 
                                            -- TABLA.reportearea_id, 
                                            COUNT(TABLA.reportevibracionevaluacion_punto) AS total
                                        FROM
                                            reportevibracionevaluacion AS TABLA
                                        WHERE
                                            TABLA.proyecto_id = reportevibracionevaluacion.proyecto_id
                                            AND TABLA.reportearea_id = reportevibracionevaluacion.reportearea_id
                                        LIMIT 1
                                    ), 0) AS total_puntosarea,
                                    reportevibracionevaluacion.reportevibracionevaluacion_punto 
                                FROM
                                    reportevibracionevaluacion
                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                WHERE
                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reportearea.reportearea_orden ASC,
                                    reportearea.reportearea_nombre ASC,
                                    reportecategoria.reportecategoria_orden ASC,
                                    reportecategoria.reportecategoria_nombre ASC');


            // Columnas
            $ancho_col_1 = 1000;
            $ancho_col_2 = 2000;
            $ancho_col_3 = 4000;
            $ancho_col_4 = 1500;
            $ancho_col_5 = 1000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $numero_fila = 0;
            $instalacion = 'xxxx';
            $area = 'XXXX';
            $tipoexposicion = 'XXXX';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    if (($key + 0) != 0) {
                        $total = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportearea.reportearea_instalacion,
                                                -- reportevibracionevaluacion.reportearea_id,
                                                -- reportearea.reportearea_nombre,
                                                COUNT(reportevibracionevaluacion.reportevibracionevaluacion_punto) AS total
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                            GROUP BY
                                                reportevibracionevaluacion.proyecto_id,
                                                reportearea.reportearea_instalacion');


                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
                    }


                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No', $encabezado_texto);
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Método', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Puntos por área', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reportearea_instalacion;
                }


                $table->addRow(); //fila


                if ($area != $value->reportearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);

                    // $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);


                if ($tipoexposicion != $value->reportearea_tipoexposicion) {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_tipoexposicion, $texto);

                    $tipoexposicion = $value->reportearea_tipoexposicion;
                } else {
                    $table->addCell($ancho_col_4, $continua_fila);
                }


                if ($area != $value->reportearea_nombre) {
                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosarea, $texto);

                    $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila);
                }
            }


            $total = DB::select('SELECT
                                        reportevibracionevaluacion.proyecto_id,
                                        reportearea.reportearea_instalacion,
                                        -- reportevibracionevaluacion.reportearea_id,
                                        -- reportearea.reportearea_nombre,
                                        COUNT(reportevibracionevaluacion.reportevibracionevaluacion_punto) AS total
                                    FROM
                                        reportevibracionevaluacion
                                        LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                    WHERE
                                        reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                    GROUP BY
                                        reportevibracionevaluacion.proyecto_id,
                                        reportearea.reportearea_instalacion');


            $total_evaluacion = 0;
            if (count($total) > 0) {
                $total_evaluacion = $total[0]->total;
            }

            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($total_evaluacion, $textonegrita);


            $plantillaword->setComplexBlock('TABLA_6_2', $table);


            // TABLA 7.1 Resultados
            //================================================================================


            $evaluacion = DB::select('SELECT
                                            reportevibracionevaluacion.proyecto_id,
                                            reportevibracionevaluacion.id,
                                            reportearea.reportearea_instalacion,
                                            reportevibracionevaluacion.reportearea_id,
                                            reportearea.reportearea_orden,
                                            reportearea.reportearea_nombre,
                                            reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                            reportearea.reportevibracionarea_porcientooperacion,
                                            reportevibracionevaluacion.reportecategoria_id,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre,
                                            reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                            reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                            reportearea.reportearea_tipoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                            IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                            IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                            reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                            IFNULL((
                                                IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                            ), "N/A") AS resultado
                                        FROM
                                            reportevibracionevaluacion
                                            LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                        WHERE
                                            reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


            if (count($evaluacion) > 0) {
                switch (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0)) {
                    case (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2):


                        $datos = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "#000000") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "#000000") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "#000000") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "#000000") AS ay3_color,

                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                        if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                            $font_size = 8;
                            $bgColor_encabezado = '#0C3F64';
                            $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
                            $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
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


                            // Columnas
                            $ancho_col_1 = 1300;
                            $ancho_col_2 = 1300;
                            $ancho_col_3 = 1550;
                            $ancho_col_4 = 1300;
                            $ancho_col_5 = 1300;
                            $ancho_col_6 = 1550;
                            $ancho_col_7 = 1500;


                            // Crear tabla
                            $table = null;
                            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                            $punto = 'XXXXX';
                            foreach ($datos as $key => $value) {
                                if ($punto != $value->reportevibracionevaluacion_punto) {
                                    // $punto = $value->reportevibracionevaluacion_punto;


                                    // Encabezado
                                    $table->addRow(); //fila
                                    $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('No. de medición:', $encabezado_texto);
                                    $table->addCell(($ancho_col_3 + $ancho_col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportevibracionevaluacion_punto, $texto);
                                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($izquierda)->addText('Área:', $encabezado_texto);
                                    $table->addCell(($ancho_col_6 + $ancho_col_7), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportearea_nombre, $texto);
                                    $table->addRow(); //fila
                                    $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('Punto de evaluación:', $encabezado_texto);
                                    $table->addCell(($ancho_col_3 + $ancho_col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportevibracionevaluacion_puntoevaluacion, $texto);
                                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($izquierda)->addText('Categoría:', $encabezado_texto);
                                    $table->addCell(($ancho_col_6 + $ancho_col_7), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportecategoria_nombre, $texto);
                                    $table->addRow(); //fila
                                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Frecuencia central de tercio de octava (Hz)', $encabezado_texto);
                                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Medición de aceleración longitudinal en (az) (m/s²)', $encabezado_texto);
                                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Límite de aceleración longitudinal en (az) (m/s²) para ' . $value->reportevibracionevaluacion_tiempoexposicion . ' de exposición', $encabezado_texto);
                                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Medición de aceleración transversal en (ax) (m/s²)', $encabezado_texto);
                                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Medición de aceleración transversal (ay) (m/s²)', $encabezado_texto);
                                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite de aceleración transversal en (ax, ay) (m/s²) para ' . $value->reportevibracionevaluacion_tiempoexposicion . ' de exposición', $encabezado_texto);
                                    $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);
                                }


                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_frecuencia, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_az1, array('color' => $value->az1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_azlimite, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ax1, array('color' => $value->ax1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ay1, array('color' => $value->ay1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_axylimite, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));


                                if ($punto != $value->reportevibracionevaluacion_punto) {
                                    if ($value->resultado == "Dentro de norma") //Verde
                                    {
                                        $text_color = "#000000";
                                        $bgColor = "#00FF00";

                                        $table->addCell($ancho_col_7, array('vMerge' => 'restart', 'bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                    } else {
                                        $text_color = "#FFFFFF";
                                        $bgColor = "#FF0000";

                                        $table->addCell($ancho_col_7, array('vMerge' => 'restart', 'bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                    }


                                    $punto = $value->reportevibracionevaluacion_punto;
                                } else {
                                    $table->addCell($ancho_col_7, $continua_fila);
                                }
                            }
                        } else {
                            $font_size = 7;
                            $bgColor_encabezado = '#0C3F64';
                            $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
                            $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
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


                            // Columnas
                            $ancho_col_1 = 730;
                            $ancho_col_2 = 730;
                            $ancho_col_3 = 730;
                            $ancho_col_4 = 730;
                            $ancho_col_5 = 730;
                            $ancho_col_6 = 730;
                            $ancho_col_7 = 730;
                            $ancho_col_8 = 730;
                            $ancho_col_9 = 730;
                            $ancho_col_10 = 730;
                            $ancho_col_11 = 730;
                            $ancho_col_12 = 730;
                            $ancho_col_13 = 730;


                            // Crear tabla
                            $table = null;
                            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                            $punto = 'XXXXX';
                            foreach ($datos as $key => $value) {
                                if ($punto != $value->reportevibracionevaluacion_punto) {
                                    // $punto = $value->reportevibracionevaluacion_punto;


                                    // Encabezado
                                    $table->addRow(); //fila
                                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('No. de medición:', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportevibracionevaluacion_punto, $texto);
                                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('Área:', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportearea_nombre, $texto);
                                    $table->addRow(); //fila
                                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('Punto de evaluación:', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportevibracionevaluacion_puntoevaluacion, $texto);
                                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('Categoría:', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText($value->reportecategoria_nombre, $texto);
                                    $table->addRow(); //fila
                                    $table->addCell(null, $encabezado_celda)->addTextRun($centrado)->addText('Frecuencia central de tercio de octava (Hz)', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Medición de aceleración longitudinal en (az) (m/s²)', $encabezado_texto);
                                    $table->addCell(null, $encabezado_celda)->addTextRun($centrado)->addText('Límite de aceleración longitudinal en (az) (m/s²) para ' . $value->reportevibracionevaluacion_tiempoexposicion . ' de exposición', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Medición de aceleración transversal en (ax) (m/s²)', $encabezado_texto);
                                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Medición de aceleración transversal (ay) (m/s²)', $encabezado_texto);
                                    $table->addCell(null, $encabezado_celda)->addTextRun($centrado)->addText('Límite de aceleración transversal en (ax, ay) (m/s²) para ' . $value->reportevibracionevaluacion_tiempoexposicion . ' de exposición', $encabezado_texto);
                                    $table->addCell(null, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);
                                }


                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_frecuencia, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_az1, array('color' => $value->az1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_az2, array('color' => $value->az2_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_az3, array('color' => $value->az3_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_azlimite, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ax1, array('color' => $value->ax1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ax2, array('color' => $value->ax2_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ax3, array('color' => $value->ax3_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_9, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ay1, array('color' => $value->ay1_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_10, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ay2, array('color' => $value->ay2_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_11, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_ay3, array('color' => $value->ay3_color, 'size' => $font_size, 'bold' => false, 'name' => $fuente));
                                $table->addCell($ancho_col_12, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluaciondatos_axylimite, array('color' => '#000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente));


                                if ($punto != $value->reportevibracionevaluacion_punto) {
                                    if ($value->resultado == "Dentro de norma") //Verde
                                    {
                                        $text_color = "#000000";
                                        $bgColor = "#00FF00";

                                        $table->addCell($ancho_col_13, array('vMerge' => 'restart', 'bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                    } else {
                                        $text_color = "#FFFFFF";
                                        $bgColor = "#FF0000";

                                        $table->addCell($ancho_col_13, array('vMerge' => 'restart', 'bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                                    }


                                    $punto = $value->reportevibracionevaluacion_punto;
                                } else {
                                    $table->addCell($ancho_col_13, $continua_fila);
                                }
                            }
                        }


                        break;
                    default:

                        $font_size = 8;
                        $bgColor_encabezado = '#0C3F64';
                        $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
                        $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
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


                        // Columnas
                        $ancho_col_1 = 1000;
                        $ancho_col_2 = 1000;
                        $ancho_col_3 = 1500;
                        $ancho_col_4 = 1500;
                        $ancho_col_5 = 2000;
                        $ancho_col_6 = 1000;
                        $ancho_col_7 = 1000;
                        $ancho_col_8 = 1500;


                        // Crear tabla
                        $table = null;
                        $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                        // Encabezado
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(null, array('gridSpan' => 8, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Tabla de resultados de vibraciones de cuerpo entero', $encabezado_texto);
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No. medición', $encabezado_texto);
                        $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de medición', $encabezado_texto);
                        $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Identificación', $encabezado_texto);
                        $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Promedio (m/seg²)', $encabezado_texto);
                        $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Valor Máximo Permisible (m/seg²)', $encabezado_texto);
                        $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


                        foreach ($evaluacion as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluacion_punto, $texto);
                            $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluacion_fecha, $texto);
                            $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                            $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                            $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluacion_puntoevaluacion, $texto);
                            $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluacion_promedio, $texto);
                            $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reportevibracionevaluacion_valormaximo, $texto);


                            if ($value->resultado == "Dentro de norma") //Verde
                            {
                                $text_color = "#000000";
                                $bgColor = "#00FF00";

                                $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                            } else {
                                $text_color = "#FFFFFF";
                                $bgColor = "#FF0000";

                                $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                            }
                        }


                        break;
                }
            } else {
                // Columnas
                $ancho_col_1 = 1300;
                $ancho_col_2 = 1300;
                $ancho_col_3 = 1550;
                $ancho_col_4 = 1300;
                $ancho_col_5 = 1300;
                $ancho_col_6 = 1550;
                $ancho_col_7 = 1500;


                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                // Encabezado
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('No. de medición:', $encabezado_texto);
                $table->addCell(($ancho_col_3 + $ancho_col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText('', $encabezado_texto);
                $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($izquierda)->addText('Área:', $encabezado_texto);
                $table->addCell(($ancho_col_6 + $ancho_col_7), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText('', $encabezado_texto);
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($izquierda)->addText('Punto de evaluación:', $encabezado_texto);
                $table->addCell(($ancho_col_3 + $ancho_col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText('', $encabezado_texto);
                $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($izquierda)->addText('Categoría:', $encabezado_texto);
                $table->addCell(($ancho_col_6 + $ancho_col_7), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($izquierda)->addText('', $encabezado_texto);
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Frecuencia central de tercio de octava (Hz)', $encabezado_texto);
                $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Medición de aceleración longitudinal en (az) (m/s²)', $encabezado_texto);
                $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Límite de aceleración longitudinal en (az) (m/s²) del tiempo de exposición', $encabezado_texto);
                $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Medición de aceleración transversal en (ax) (m/s²)', $encabezado_texto);
                $table->addCell($ancho_col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Medición de aceleración transversal (ay) (m/s²)', $encabezado_texto);
                $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Límite de aceleración transversal en (ax, ay) (m/s²) del tiempo de exposición', $encabezado_texto);
                $table->addCell($ancho_col_7, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);
            }


            $plantillaword->setComplexBlock('TABLA_7_1', $table);


            // TABLA 7.7 Matriz de exposición laboral
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


            $perforacion = 0;

            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1) {
                    $plantillaword->setValue('MATRIZ_TEXTO', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Se anexa en formato digital.</w:t></w:r><w:r><w:t>');

                    $perforacion = 1;
                } else {
                    $plantillaword->setValue('MATRIZ_TEXTO', 'La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico vibraciones en cuerpo entero, así como información del área física y de la plantilla laboral de la instalación en cuestión.');
                }
            } else {
                $plantillaword->setValue('MATRIZ_TEXTO', 'La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico vibraciones en cuerpo entero, así como información del área física y de la plantilla laboral de la instalación en cuestión.');
            }


            // $proyecto->catregion_id = 3;
            // $perforacion = 0;
            // $evaluacion[0]->reportevibracionevaluacion_tipoevaluacion = 3;
            // $evaluacion[0]->reportevibracionevaluacion_numeromediciones = 1;


            if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 1 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
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
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        // encabezado tabla
                        $ancho_col_1 = 500;
                        $ancho_col_2 = 1000;
                        $ancho_col_3 = 1000;
                        $ancho_col_4 = 1000;
                        $ancho_col_5 = 1000;
                        $ancho_col_6 = 1000;
                        $ancho_col_7 = 800;
                        $ancho_col_8 = 1000;
                        $ancho_col_9 = 400;
                        $ancho_col_10 = 400;
                        $ancho_col_11 = 400;
                        $ancho_col_12 = 400;
                        $ancho_col_13 = 600;
                        $ancho_col_14 = 600;
                        $ancho_col_15 = 600;
                        $ancho_col_16 = 600;
                        $ancho_col_17 = 600;

                        // Crear tabla
                        $table = null;
                        $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                        $numero_registro = 0;
                        $punto = 'X';
                        $punto2 = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                if (($key + 0) > 0) {
                                    $table->addRow(); //fila
                                    $table->addCell(NULL, array('gridSpan' => 17, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                                }


                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de<w:br/>personas<w:br/>en el área', $encabezado_texto3);
                                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de<w:br/>exposición<w:br/>homogénea', $encabezado_texto3);
                                $table->addCell($ancho_col_11, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell($ancho_col_12, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell($ancho_col_12, $continua_fila3);
                                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_15, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                                $table->addCell($ancho_col_16, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                                $table->addCell($ancho_col_17, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);
                            }


                            $table->addRow(); //fila


                            if ($punto2 != $value->reportevibracionevaluacion_punto) {
                                $punto2 = $value->reportevibracionevaluacion_punto;


                                $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);
                                $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->personas_area, $texto3);
                                $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->geh, $texto3);
                                $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                            } else {
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
                            }


                            $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                            $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                            $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                            $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                            $table->addCell($ancho_col_16, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                            $table->addCell($ancho_col_17, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                        }


                        $table->addRow(); //fila
                        $table->addCell(NULL, array('gridSpan' => 17, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                    } else {
                        // encabezado tabla
                        $ancho_col_1 = 500;
                        $ancho_col_2 = 1000;
                        $ancho_col_3 = 1000;
                        $ancho_col_4 = 1000;
                        $ancho_col_5 = 1000;
                        $ancho_col_6 = 1000;
                        $ancho_col_7 = 800;
                        $ancho_col_8 = 1000;
                        $ancho_col_9 = 400;
                        $ancho_col_10 = 400;
                        $ancho_col_11 = 400;
                        $ancho_col_12 = 400;
                        $ancho_col_13 = 400;
                        $ancho_col_14 = 400;
                        $ancho_col_15 = 400;
                        $ancho_col_16 = 400;
                        $ancho_col_17 = 400;
                        $ancho_col_18 = 400;
                        $ancho_col_19 = 400;
                        $ancho_col_20 = 400;
                        $ancho_col_21 = 400;
                        $ancho_col_22 = 400;
                        $ancho_col_23 = 400;

                        // Crear tabla
                        $table = null;
                        $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                        $numero_registro = 0;
                        $punto = 'X';
                        $punto2 = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                if (($key + 0) > 0) {
                                    $table->addRow(); //fila
                                    $table->addCell(NULL, array('gridSpan' => 23, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                                }


                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;


                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de<w:br/>personas<w:br/>en el área', $encabezado_texto3);
                                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de<w:br/>exposición<w:br/>homogénea', $encabezado_texto3);
                                $table->addCell($ancho_col_11, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 12, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell($ancho_col_12, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell($ancho_col_12, $continua_fila3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_16, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                                $table->addCell($ancho_col_23, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);
                            }


                            $table->addRow(); //fila


                            if ($punto2 != $value->reportevibracionevaluacion_punto) {
                                $punto2 = $value->reportevibracionevaluacion_punto;


                                $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);
                                $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->personas_area, $texto3);
                                $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->geh, $texto3);
                                $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                            } else {
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
                            }


                            $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                            $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                            $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az2, $texto3);
                            $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az3, $texto3);
                            $table->addCell($ancho_col_16, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                            $table->addCell($ancho_col_17, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                            $table->addCell($ancho_col_18, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax2, $texto3);
                            $table->addCell($ancho_col_19, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax3, $texto3);
                            $table->addCell($ancho_col_20, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                            $table->addCell($ancho_col_21, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay2, $texto3);
                            $table->addCell($ancho_col_22, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay3, $texto3);
                            $table->addCell($ancho_col_23, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                        }


                        $table->addRow(); //fila
                        $table->addCell(NULL, array('gridSpan' => 23, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                    }
                } else {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
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
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                IFNULL((
                                                    IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                ), "N/A") AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


                    // encabezado tabla
                    $ancho_col_1 = 500;
                    $ancho_col_2 = 1000;
                    $ancho_col_3 = 1000;
                    $ancho_col_4 = 1000;
                    $ancho_col_5 = 1500;
                    $ancho_col_6 = 2000;
                    $ancho_col_7 = 800;
                    $ancho_col_8 = 2000;
                    $ancho_col_9 = 600;
                    $ancho_col_10 = 600;
                    $ancho_col_11 = 800;
                    $ancho_col_12 = 750;
                    $ancho_col_13 = 750;

                    // Crear tabla
                    $table = null;
                    $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                    $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                    $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                    $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                    $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de<w:br/>personas<w:br/>en el área', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de<w:br/>exposición<w:br/>homogénea', $encabezado_texto3);
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('(Frecuencia / Medición de aceleración / LMPE)', $encabezado_texto3);

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
                    $table->addCell($ancho_col_11, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior', $encabezado_texto3);
                    $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

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
                    $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Promedio (m/seg²)', $encabezado_texto3);
                    $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Valor Máximo Permisible (m/seg²)', $encabezado_texto3);


                    $numero_registro = 0;
                    $subdir = 'XXXX';
                    $activo = 'XXXX';
                    $instalacion = 'XXXX';
                    $area = 'XXXX';
                    $trabajador = 'XXXX';
                    $ficha = 'XXXX';
                    $categoria = 'XXXX';
                    foreach ($matriz as $key => $value) {
                        $numero_registro += 1;


                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($numero_registro, $texto3);


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


                        if ($instalacion != $value->reportearea_instalacion) {
                            $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                            $instalacion = $value->reportearea_instalacion;
                        } else {
                            $table->addCell($ancho_col_4, $continua_fila3);
                        }


                        if ($area != $value->reportearea_nombre) {
                            $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);

                            // $area = $value->reportearea_nombre;
                        } else {
                            $table->addCell($ancho_col_5, $continua_fila3);
                        }


                        if ($trabajador != $value->reportevibracionevaluacion_nombre) {
                            $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);

                            $trabajador = $value->reportevibracionevaluacion_nombre;
                        } else {
                            $table->addCell($ancho_col_6, $continua_fila3);
                        }


                        if ($ficha != $value->reportevibracionevaluacion_ficha) {
                            $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);

                            $ficha = $value->reportevibracionevaluacion_ficha;
                        } else {
                            $table->addCell($ancho_col_7, $continua_fila3);
                        }


                        if ($categoria != $value->reportecategoria_nombre) {
                            $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);

                            $categoria = $value->reportecategoria_nombre;
                        } else {
                            $table->addCell($ancho_col_8, $continua_fila3);
                        }


                        if ($area != $value->reportearea_nombre) {
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->personas_area, $texto3);
                            $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->geh, $texto3);
                            $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);

                            $area = $value->reportearea_nombre;
                        } else {
                            $table->addCell($ancho_col_9, $continua_fila3);
                            $table->addCell($ancho_col_10, $continua_fila3);
                            $table->addCell($ancho_col_11, $continua_fila3);
                        }


                        $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_promedio, $texto3);
                        $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_valormaximo, $texto3);
                    }


                    $table->addRow(); //fila
                    $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                }
            } else if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 2 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
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
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        // encabezado tabla
                        $ancho_col_1 = 500;
                        $ancho_col_2 = 1000;
                        $ancho_col_3 = 1000;
                        $ancho_col_4 = 1000;
                        $ancho_col_5 = 1000;
                        $ancho_col_6 = 1000;
                        $ancho_col_7 = 800;
                        $ancho_col_8 = 1000;
                        $ancho_col_9 = 400;
                        $ancho_col_10 = 400;
                        $ancho_col_11 = 900;
                        $ancho_col_12 = 900;
                        $ancho_col_13 = 900;
                        $ancho_col_14 = 900;
                        $ancho_col_15 = 900;

                        // Crear tabla
                        $table = null;
                        $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                        $numero_registro = 0;
                        $punto = 'X';
                        $punto2 = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                if (($key + 0) > 0) {
                                    $table->addRow(); //fila
                                    $table->addCell(NULL, array('gridSpan' => 15, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                                }


                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $continua_fila3);
                                $table->addCell($ancho_col_3, $continua_fila3);
                                $table->addCell($ancho_col_4, $continua_fila3);
                                $table->addCell($ancho_col_5, $continua_fila3);
                                $table->addCell($ancho_col_6, $continua_fila3);
                                $table->addCell($ancho_col_7, $continua_fila3);
                                $table->addCell($ancho_col_8, $continua_fila3);
                                $table->addCell($ancho_col_9, $continua_fila3);
                                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                                $table->addCell($ancho_col_15, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);
                            }


                            $table->addRow(); //fila


                            if ($punto2 != $value->reportevibracionevaluacion_punto) {
                                $punto2 = $value->reportevibracionevaluacion_punto;


                                $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);
                                $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                            } else {
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $continua_fila3);
                                $table->addCell($ancho_col_3, $continua_fila3);
                                $table->addCell($ancho_col_4, $continua_fila3);
                                $table->addCell($ancho_col_5, $continua_fila3);
                                $table->addCell($ancho_col_6, $continua_fila3);
                                $table->addCell($ancho_col_7, $continua_fila3);
                                $table->addCell($ancho_col_8, $continua_fila3);
                                $table->addCell($ancho_col_9, $continua_fila3);
                            }


                            $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                            $table->addCell($ancho_col_11, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                            $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                            $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                            $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                            $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                        }


                        $table->addRow(); //fila
                        $table->addCell(NULL, array('gridSpan' => 15, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                    } else {
                        // encabezado tabla
                        $ancho_col_1 = 500;
                        $ancho_col_2 = 1000;
                        $ancho_col_3 = 1000;
                        $ancho_col_4 = 1000;
                        $ancho_col_5 = 1000;
                        $ancho_col_6 = 1000;
                        $ancho_col_7 = 800;
                        $ancho_col_8 = 1000;
                        $ancho_col_9 = 400;
                        $ancho_col_10 = 400;
                        $ancho_col_11 = 400;
                        $ancho_col_12 = 400;
                        $ancho_col_13 = 400;
                        $ancho_col_14 = 400;
                        $ancho_col_15 = 400;
                        $ancho_col_16 = 400;
                        $ancho_col_17 = 400;
                        $ancho_col_18 = 400;
                        $ancho_col_19 = 400;
                        $ancho_col_20 = 400;
                        $ancho_col_21 = 400;

                        // Crear tabla
                        $table = null;
                        $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                        $numero_registro = 0;
                        $punto = 'X';
                        $punto2 = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                if (($key + 0) > 0) {
                                    $table->addRow(); //fila
                                    $table->addCell(NULL, array('gridSpan' => 21, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                                }


                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;


                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                                $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                                $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 12, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                                $table->addRow(); //fila
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $continua_fila3);
                                $table->addCell($ancho_col_3, $continua_fila3);
                                $table->addCell($ancho_col_4, $continua_fila3);
                                $table->addCell($ancho_col_5, $continua_fila3);
                                $table->addCell($ancho_col_6, $continua_fila3);
                                $table->addCell($ancho_col_7, $continua_fila3);
                                $table->addCell($ancho_col_8, $continua_fila3);
                                $table->addCell($ancho_col_9, $continua_fila3);
                                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                                $table->addRow(); //fila
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
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                                $table->addCell($ancho_col_21, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);
                            }


                            $table->addRow(); //fila


                            if ($punto2 != $value->reportevibracionevaluacion_punto) {
                                $punto2 = $value->reportevibracionevaluacion_punto;


                                $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);
                                $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                            } else {
                                $table->addCell($ancho_col_1, $continua_fila3);
                                $table->addCell($ancho_col_2, $continua_fila3);
                                $table->addCell($ancho_col_3, $continua_fila3);
                                $table->addCell($ancho_col_4, $continua_fila3);
                                $table->addCell($ancho_col_5, $continua_fila3);
                                $table->addCell($ancho_col_6, $continua_fila3);
                                $table->addCell($ancho_col_7, $continua_fila3);
                                $table->addCell($ancho_col_8, $continua_fila3);
                                $table->addCell($ancho_col_9, $continua_fila3);
                            }


                            $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                            $table->addCell($ancho_col_11, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                            $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az2, $texto3);
                            $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az3, $texto3);
                            $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                            $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                            $table->addCell($ancho_col_16, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax2, $texto3);
                            $table->addCell($ancho_col_17, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax3, $texto3);
                            $table->addCell($ancho_col_18, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                            $table->addCell($ancho_col_19, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay2, $texto3);
                            $table->addCell($ancho_col_20, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay3, $texto3);
                            $table->addCell($ancho_col_21, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                        }


                        $table->addRow(); //fila
                        $table->addCell(NULL, array('gridSpan' => 21, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                    }
                } else {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
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
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                IFNULL((
                                                    IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                ), "N/A") AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


                    // encabezado tabla
                    $ancho_col_1 = 500;
                    $ancho_col_2 = 1000;
                    $ancho_col_3 = 1000;
                    $ancho_col_4 = 1000;
                    $ancho_col_5 = 2000;
                    $ancho_col_6 = 2000;
                    $ancho_col_7 = 800;
                    $ancho_col_8 = 2000;
                    $ancho_col_9 = 1000;
                    $ancho_col_10 = 1500;

                    // Crear tabla
                    $table = null;
                    $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                    $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                    $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                    $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                    $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('(Frecuencia / Medición de aceleración / LMPE)', $encabezado_texto3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell($ancho_col_2, $continua_fila3);
                    $table->addCell($ancho_col_3, $continua_fila3);
                    $table->addCell($ancho_col_4, $continua_fila3);
                    $table->addCell($ancho_col_5, $continua_fila3);
                    $table->addCell($ancho_col_6, $continua_fila3);
                    $table->addCell($ancho_col_7, $continua_fila3);
                    $table->addCell($ancho_col_8, $continua_fila3);
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Extremidad superior', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);


                    $numero_registro = 0;
                    $subdir = 'XXXX';
                    $activo = 'XXXX';
                    $instalacion = 'XXXX';
                    $area = 'XXXX';
                    $trabajador = 'XXXX';
                    $ficha = 'XXXX';
                    $categoria = 'XXXX';
                    foreach ($matriz as $key => $value) {
                        $numero_registro += 1;


                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($numero_registro, $texto3);


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


                        if ($instalacion != $value->reportearea_instalacion) {
                            $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                            $instalacion = $value->reportearea_instalacion;
                        } else {
                            $table->addCell($ancho_col_4, $continua_fila3);
                        }


                        if ($area != $value->reportearea_nombre) {
                            $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_nombre, $texto3);

                            // $area = $value->reportearea_nombre;
                        } else {
                            $table->addCell($ancho_col_5, $continua_fila3);
                        }


                        if ($trabajador != $value->reportevibracionevaluacion_nombre) {
                            $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);

                            $trabajador = $value->reportevibracionevaluacion_nombre;
                        } else {
                            $table->addCell($ancho_col_6, $continua_fila3);
                        }


                        if ($ficha != $value->reportevibracionevaluacion_ficha) {
                            $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);

                            $ficha = $value->reportevibracionevaluacion_ficha;
                        } else {
                            $table->addCell($ancho_col_7, $continua_fila3);
                        }


                        if ($categoria != $value->reportecategoria_nombre) {
                            $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);

                            $categoria = $value->reportecategoria_nombre;
                        } else {
                            $table->addCell($ancho_col_8, $continua_fila3);
                        }


                        if ($area != $value->reportearea_nombre) {
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);

                            $area = $value->reportearea_nombre;
                        } else {
                            $table->addCell($ancho_col_9, $continua_fila3);
                        }


                        $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_promedio . ' / ' . $value->reportevibracionevaluacion_valormaximo, $texto3);
                    }

                    $table->addRow(); //fila
                    $table->addCell(NULL, array('gridSpan' => 10, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                }
            } else if (count($evaluacion) > 0) {
                $categorias_evaluadas = DB::select('SELECT
                                                        reportevibracionevaluacion.proyecto_id,
                                                        reportevibracionevaluacion.reportecategoria_id,
                                                        reportecategoria.reportecategoria_orden,
                                                        reportecategoria.reportecategoria_nombre,
                                                        COUNT( reportevibracionevaluacion.reportevibracionevaluacion_punto ) AS total_mediciones 
                                                    FROM
                                                        reportevibracionevaluacion
                                                        LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id 
                                                    WHERE
                                                        reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    GROUP BY
                                                        reportevibracionevaluacion.proyecto_id,
                                                        reportevibracionevaluacion.reportecategoria_id,
                                                        reportecategoria.reportecategoria_orden,
                                                        reportecategoria.reportecategoria_nombre
                                                    ORDER BY
                                                        reportecategoria.reportecategoria_orden ASC');


                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                $numero_registro = 0;


                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    foreach ($categorias_evaluadas as $key1 => $categoria) {
                        $numero_registro += 1;


                        $matriz = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.id,
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
                                                    reportearea.reportearea_instalacion,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                    reportearea.reportevibracionarea_porcientooperacion,
                                                    (
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id,
                                                            SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        LIMIT 1
                                                    ) AS personas_area,
                                                    reportevibracionevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                    IFNULL((
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id, 
                                                            -- reporteareacategoria.reportecategoria_id, 
                                                            -- reporteareacategoria.reporteareacategoria_total, 
                                                            -- reporteareacategoria.reporteareacategoria_actividades,
                                                            reporteareacategoria.reporteareacategoria_geh
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                            AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                        LIMIT 1
                                                    ), 1) AS geh,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                    reportearea.reportearea_tipoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                    IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                    IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                    IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                    
                                                    IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                    IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                    IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                    
                                                    IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                    IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                    IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                    (
                                                        SELECT
                                                            -- DATOS.reportevibracionevaluacion_id,
                                                            -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                            SUM(
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            ) AS total_fueranorma
                                                            -- IF(SUM(
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            -- ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                            -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                            -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                        FROM
                                                            reportevibracionevaluaciondatos AS DATOS
                                                        WHERE
                                                            DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                        GROUP BY
                                                            DATOS.reportevibracionevaluacion_id
                                                        LIMIT 1
                                                    ) AS mediciones_fueranorma,
                                                    (
                                                        SELECT
                                                            -- DATOS.reportevibracionevaluacion_id,
                                                            -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                            -- SUM(
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            -- ) AS total_fueranorma,
                                                            IF(SUM(
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                            -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                            -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                        FROM
                                                            reportevibracionevaluaciondatos AS DATOS
                                                        WHERE
                                                            DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                        GROUP BY
                                                            DATOS.reportevibracionevaluacion_id
                                                        LIMIT 1
                                                    ) AS resultado
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                    RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reportevibracionevaluacion.reportecategoria_id = ' . $categoria->reportecategoria_id . ' 
                                                ORDER BY
                                                    mediciones_fueranorma DESC,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                    (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC
                                                LIMIT 20');


                        if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                            // encabezado tabla
                            $ancho_col_1 = 500;
                            $ancho_col_2 = 1000;
                            $ancho_col_3 = 1000;
                            $ancho_col_4 = 1000;
                            $ancho_col_5 = 1000;
                            $ancho_col_6 = 800;
                            $ancho_col_7 = 1500;
                            $ancho_col_8 = 800;
                            $ancho_col_9 = 800;
                            $ancho_col_10 = 1000;
                            $ancho_col_11 = 1000;
                            $ancho_col_12 = 1000;
                            $ancho_col_13 = 1000;
                            $ancho_col_14 = 1000;


                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                            $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                            $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Coordinación', $encabezado_texto3);
                            $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                            $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                            $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                            $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                            $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $continua_fila3);
                            $table->addCell($ancho_col_3, $continua_fila3);
                            $table->addCell($ancho_col_4, $continua_fila3);
                            $table->addCell($ancho_col_5, $continua_fila3);
                            $table->addCell($ancho_col_6, $continua_fila3);
                            $table->addCell($ancho_col_7, $continua_fila3);
                            $table->addCell($ancho_col_8, $continua_fila3);
                            $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $continua_fila3);
                            $table->addCell($ancho_col_3, $continua_fila3);
                            $table->addCell($ancho_col_4, $continua_fila3);
                            $table->addCell($ancho_col_5, $continua_fila3);
                            $table->addCell($ancho_col_6, $continua_fila3);
                            $table->addCell($ancho_col_7, $continua_fila3);
                            $table->addCell($ancho_col_8, $continua_fila3);
                            $table->addCell($ancho_col_9, $continua_fila3);
                            $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                            $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                            $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                            $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                            $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);


                            foreach ($matriz as $key => $value) {
                                $table->addRow(); //fila


                                if (($key + 0) == 0) {
                                    $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                    $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                    $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                    $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                    $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                    $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                    $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                    $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                                } else {
                                    $table->addCell($ancho_col_1, $continua_fila3);
                                    $table->addCell($ancho_col_2, $continua_fila3);
                                    $table->addCell($ancho_col_3, $continua_fila3);
                                    $table->addCell($ancho_col_4, $continua_fila3);
                                    $table->addCell($ancho_col_5, $continua_fila3);
                                    $table->addCell($ancho_col_6, $continua_fila3);
                                    $table->addCell($ancho_col_7, $continua_fila3);
                                    $table->addCell($ancho_col_8, $continua_fila3);
                                }


                                $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                                $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                                $table->addCell($ancho_col_11, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                                $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                                $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                                $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                            }


                            $table->addRow(); //fila
                            $table->addCell(NULL, array('gridSpan' => 14, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                        } else {
                            // encabezado tabla
                            $ancho_col_1 = 500;
                            $ancho_col_2 = 1000;
                            $ancho_col_3 = 1000;
                            $ancho_col_4 = 1000;
                            $ancho_col_5 = 1000;
                            $ancho_col_6 = 600;
                            $ancho_col_7 = 1000;
                            $ancho_col_8 = 600;
                            $ancho_col_9 = 500;
                            $ancho_col_10 = 500;
                            $ancho_col_11 = 500;
                            $ancho_col_12 = 500;
                            $ancho_col_13 = 500;
                            $ancho_col_14 = 500;
                            $ancho_col_15 = 500;
                            $ancho_col_16 = 500;
                            $ancho_col_17 = 500;
                            $ancho_col_18 = 500;
                            $ancho_col_19 = 500;
                            $ancho_col_20 = 500;


                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                            $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                            $table->addCell(NULL, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                            $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Coordinación', $encabezado_texto3);
                            $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                            $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                            $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                            $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                            $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 12, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $continua_fila3);
                            $table->addCell($ancho_col_3, $continua_fila3);
                            $table->addCell($ancho_col_4, $continua_fila3);
                            $table->addCell($ancho_col_5, $continua_fila3);
                            $table->addCell($ancho_col_6, $continua_fila3);
                            $table->addCell($ancho_col_7, $continua_fila3);
                            $table->addCell($ancho_col_8, $continua_fila3);
                            $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $continua_fila3);
                            $table->addCell($ancho_col_2, $continua_fila3);
                            $table->addCell($ancho_col_3, $continua_fila3);
                            $table->addCell($ancho_col_4, $continua_fila3);
                            $table->addCell($ancho_col_5, $continua_fila3);
                            $table->addCell($ancho_col_6, $continua_fila3);
                            $table->addCell($ancho_col_7, $continua_fila3);
                            $table->addCell($ancho_col_8, $continua_fila3);
                            $table->addCell($ancho_col_9, $continua_fila3);
                            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                            $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                            $table->addCell($ancho_col_20, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);

                            foreach ($matriz as $key => $value) {
                                $table->addRow(); //fila


                                if (($key + 0) == 0) {
                                    $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                                    $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                                    $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                                    $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportearea_instalacion, $texto3);
                                    $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_nombre, $texto3);
                                    $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportevibracionevaluacion_ficha, $texto3);
                                    $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($value->reportecategoria_nombre, $texto3);
                                    $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);
                                } else {
                                    $table->addCell($ancho_col_1, $continua_fila3);
                                    $table->addCell($ancho_col_2, $continua_fila3);
                                    $table->addCell($ancho_col_3, $continua_fila3);
                                    $table->addCell($ancho_col_4, $continua_fila3);
                                    $table->addCell($ancho_col_5, $continua_fila3);
                                    $table->addCell($ancho_col_6, $continua_fila3);
                                    $table->addCell($ancho_col_7, $continua_fila3);
                                    $table->addCell($ancho_col_8, $continua_fila3);
                                }


                                $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_frecuencia, $texto3);
                                $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az1, $texto3);
                                $table->addCell($ancho_col_11, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az2, $texto3);
                                $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_az3, $texto3);
                                $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_azlimite, $texto3);
                                $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax1, $texto3);
                                $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax2, $texto3);
                                $table->addCell($ancho_col_16, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ax3, $texto3);
                                $table->addCell($ancho_col_17, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay1, $texto3);
                                $table->addCell($ancho_col_18, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay2, $texto3);
                                $table->addCell($ancho_col_19, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_ay3, $texto3);
                                $table->addCell($ancho_col_20, $celda3)->addTextRun($centrado3)->addText($value->reportevibracionevaluaciondatos_axylimite, $texto3);
                            }


                            $table->addRow(); //fila
                            $table->addCell(NULL, array('gridSpan' => 20, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                        }
                    }
                } else {
                    // encabezado tabla
                    $ancho_col_1 = 500;
                    $ancho_col_2 = 1500;
                    $ancho_col_3 = 1500;
                    $ancho_col_4 = 1500;
                    $ancho_col_5 = 2000;
                    $ancho_col_6 = 800;
                    $ancho_col_7 = 2500;
                    $ancho_col_8 = 1000;
                    $ancho_col_9 = 2000;

                    // Crear tabla
                    $table = null;
                    $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                    $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                    $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Coordinación', $encabezado_texto3);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                    $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                    $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                    $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('(Frecuencia / Medición de aceleración / LMPE)', $encabezado_texto3);

                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila3);
                    $table->addCell($ancho_col_2, $continua_fila3);
                    $table->addCell($ancho_col_3, $continua_fila3);
                    $table->addCell($ancho_col_4, $continua_fila3);
                    $table->addCell($ancho_col_5, $continua_fila3);
                    $table->addCell($ancho_col_6, $continua_fila3);
                    $table->addCell($ancho_col_7, $continua_fila3);
                    $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Extremidad superior', $encabezado_texto3);
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);


                    $subdir = 'XXXX';
                    $activo = 'XXXX';
                    $instalacion = 'XXXX';
                    $area = 'XXXX';
                    $trabajador = 'XXXX';
                    $ficha = 'XXXX';
                    $categoria = 'XXXX';
                    foreach ($categorias_evaluadas as $key1 => $categoria) {
                        $matriz = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.id,
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
                                                    reportearea.reportearea_instalacion,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                    reportearea.reportevibracionarea_porcientooperacion,
                                                    (
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id,
                                                            SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        LIMIT 1
                                                    ) AS personas_area,
                                                    reportevibracionevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                    IFNULL((
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id, 
                                                            -- reporteareacategoria.reportecategoria_id, 
                                                            -- reporteareacategoria.reporteareacategoria_total, 
                                                            -- reporteareacategoria.reporteareacategoria_actividades,
                                                            reporteareacategoria.reporteareacategoria_geh
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                            AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                        LIMIT 1
                                                    ), 1) AS geh,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                    reportearea.reportearea_tipoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                    IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                    IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                    IFNULL((
                                                        IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                    ), "N/A") AS resultado
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reportevibracionevaluacion.reportecategoria_id = ' . $categoria->reportecategoria_id . ' 
                                                ORDER BY
                                                    (reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) DESC
                                                LIMIT 1');


                        if (count($matriz) > 0) {
                            $numero_registro += 1;


                            $table->addRow(); //fila
                            $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($numero_registro, $texto3);


                            if ($subdir != $matriz[0]->catsubdireccion_nombre) {
                                $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->catsubdireccion_nombre, $texto3);
                                $subdir = $matriz[0]->catsubdireccion_nombre;
                            } else {
                                $table->addCell($ancho_col_2, $continua_fila3);
                            }


                            if ($activo != $matriz[0]->gerencia_activo) {
                                $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->gerencia_activo, $texto3);
                                $activo = $matriz[0]->gerencia_activo;
                            } else {
                                $table->addCell($ancho_col_3, $continua_fila3);
                            }


                            if ($instalacion != $matriz[0]->reportearea_instalacion) {
                                $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->reportearea_instalacion, $texto3);
                                $instalacion = $matriz[0]->reportearea_instalacion;
                            } else {
                                $table->addCell($ancho_col_4, $continua_fila3);
                            }


                            if ($trabajador != $matriz[0]->reportevibracionevaluacion_nombre) {
                                $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->reportevibracionevaluacion_nombre, $texto3);

                                $trabajador = $matriz[0]->reportevibracionevaluacion_nombre;
                            } else {
                                $table->addCell($ancho_col_5, $continua_fila3);
                            }


                            if ($ficha != $matriz[0]->reportevibracionevaluacion_ficha) {
                                $table->addCell($ancho_col_6, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->reportevibracionevaluacion_ficha, $texto3);

                                $ficha = $matriz[0]->reportevibracionevaluacion_ficha;
                            } else {
                                $table->addCell($ancho_col_6, $continua_fila3);
                            }


                            if ($categoria != $matriz[0]->reportecategoria_nombre) {
                                $table->addCell($ancho_col_7, $combinar_fila3)->addTextRun($centrado3)->addText($matriz[0]->reportecategoria_nombre, $texto3);

                                $categoria = $matriz[0]->reportecategoria_nombre;
                            } else {
                                $table->addCell($ancho_col_7, $continua_fila3);
                            }


                            if ($area != $matriz[0]->reportearea_nombre) {
                                $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText('N/A', $texto3);

                                $area = $matriz[0]->reportearea_nombre;
                            } else {
                                $table->addCell($ancho_col_8, $continua_fila3);
                            }


                            $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($matriz[0]->reportevibracionevaluacion_promedio . ' / ' . $matriz[0]->reportevibracionevaluacion_valormaximo, $texto3);
                        }
                    }


                    $table->addRow(); //fila
                    $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda3)->addText('N/P: No Proporcionado<w:br/>N/A: No Aplica', $texto3);
                }
            } else {
                // encabezado tabla
                $ancho_col_1 = 500;
                $ancho_col_2 = 1000;
                $ancho_col_3 = 1000;
                $ancho_col_4 = 1000;
                $ancho_col_5 = 1000;
                $ancho_col_6 = 1000;
                $ancho_col_7 = 800;
                $ancho_col_8 = 1000;
                $ancho_col_9 = 400;
                $ancho_col_10 = 400;
                $ancho_col_11 = 400;
                $ancho_col_12 = 400;
                $ancho_col_13 = 600;
                $ancho_col_14 = 600;
                $ancho_col_15 = 600;
                $ancho_col_16 = 600;
                $ancho_col_17 = 600;

                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Vibraciones', $encabezado_texto3);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de<w:br/>personas<w:br/>en el área', $encabezado_texto3);
                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de<w:br/>exposición<w:br/>homogénea', $encabezado_texto3);
                $table->addCell($ancho_col_11, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Extremidad superior (Frecuencia / Medición de aceleración / Límite)', $encabezado_texto3);
                $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Cuerpo entero', $encabezado_texto3);

                $table->addRow(); //fila
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
                $table->addCell($ancho_col_12, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Frecuencia (Hz)', $encabezado_texto3);
                $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración longitudinal', $encabezado_texto3);
                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Aceleración transversal', $encabezado_texto3);

                $table->addRow(); //fila
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
                $table->addCell($ancho_col_12, $continua_fila3);
                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (az)', $encabezado_texto3);
                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (az)', $encabezado_texto3);
                $table->addCell($ancho_col_15, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ax)', $encabezado_texto3);
                $table->addCell($ancho_col_16, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Medición (ay)', $encabezado_texto3);
                $table->addCell($ancho_col_17, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Límite (ax, ay)', $encabezado_texto3);
            }


            $plantillaword->setComplexBlock('TABLA_MATRIZ', $table);


            // CONCLUSION
            //================================================================================


            $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportevibracion_conclusion));


            // DASHBOARD
            //================================================================================


            if ($request->grafica_dashboard) {
                $imagen_base64 = base64_decode(str_replace("data:image/jpeg;base64,", "", $request->grafica_dashboard));
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
                                                                            AND reporterecomendaciones.reporterecomendacionescatalogo_id = reporterecomendacionescatalogo.id
                                                                        LIMIT 1 
                                                                ), NULL) AS recomendaciones_descripcion
                                                            FROM
                                                                reporterecomendacionescatalogo
                                                            WHERE
                                                                reporterecomendacionescatalogo.agente_nombre LIKE "%' . $agente_nombre . '%"
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
                                                        AND reporterecomendaciones.agente_nombre LIKE "%' . $agente_nombre . '%" 
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
            if ($reporte->reportevibracion_responsable1documento) {
                if (file_exists(storage_path('app/' . $reporte->reportevibracion_responsable1documento))) {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportevibracion_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE1', $reporte->reportevibracion_responsable1 . "<w:br/>" . $reporte->reportevibracion_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporte->reportevibracion_responsable2documento) {
                if (file_exists(storage_path('app/' . $reporte->reportevibracion_responsable2documento))) {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportevibracion_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE2', $reporte->reportevibracion_responsable2 . "<w:br/>" . $reporte->reportevibracion_responsable2cargo);


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
                                    AND proyectoevidenciafoto.agente_nombre LIKE "%' . $agente_nombre . '%" 
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
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Evaluación de ' . $agente_nombre, $encabezado_texto);


            if (count($fotos) > 0) {
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
            } else {
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
                                                reporteplanoscarpetas.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%' . $agente_nombre . '%"');


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
                                                AND proyectoevidenciaplano.agente_nombre LIKE "%' . $carpeta->agente_nombre . '%" 
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = "' . $carpeta->reporteplanoscarpetas_nombre . '" 
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo ASC');

                    foreach ($planos as $key => $plano) {
                        $planoscarpetasvariales .= '${PLANO_' . $planocontador . '_FOTO}';

                        $plano_archivo[] = $plano->proyectoevidenciaplano_archivo;

                        $planocontador += 1;
                    }
                }
            } else {
                $plano_archivo = array();
                $planoscarpetasvariales = 'NO SE ENCONTRARÓN PLANOS QUE MOSTRAR.';
            }


            $plantillaword->setValue('PLANOS', $planoscarpetasvariales);


            // TABLA ANEXO 3, EQUIPO UTILIZADO PARA LA MEDICION
            //================================================================================


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
                                    AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                ORDER BY
                                    equipo.equipo_Descripcion ASC,
                                    equipo.equipo_Marca ASC,
                                    equipo.equipo_Modelo ASC,
                                    equipo.equipo_Serie ASC');


            // Columnas
            $ancho_col_1 = 1800;
            $ancho_col_2 = 1800;
            $ancho_col_3 = 1800;
            $ancho_col_4 = 1800;
            $ancho_col_5 = 1800;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
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
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

            // sleep(1);

            // ABRIR NUEVA PLANTILLA
            $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


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

                    // $plantillaword->setValue('PUNTO_'.$i.'_DESCRIPCION', "Punto ".$fotos[$i]->proyectoevidenciafoto_nopunto." ".$fotos[$i]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . $i . '_DESCRIPCION', $fotos[$i]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 1) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 1) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 1)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 1) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_'.($i+1).'_DESCRIPCION', "Punto ".$fotos[($i+1)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+1)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 1) . '_DESCRIPCION', $fotos[($i + 1)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 2) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 2) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 2)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 2) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_'.($i+2).'_DESCRIPCION', "Punto ".$fotos[($i+2)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+2)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 2) . '_DESCRIPCION', $fotos[($i + 2)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 3) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 3) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 3)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 3) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_'.($i+3).'_DESCRIPCION', "Punto ".$fotos[($i+3)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+3)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 3) . '_DESCRIPCION', $fotos[($i + 3)]->proyectoevidenciafoto_descripcion);
                }
            }


            // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - AGREGAR FOTOS
            //================================================================================


            for ($i = 0; $i < count($plano_archivo); $i++) {
                if (Storage::exists($plano_archivo[$i])) {
                    $plantillaword->setImageValue('PLANO_' . $i . '_FOTO', array('path' => storage_path('app/' . $plano_archivo[$i]), 'height' => 690, 'width' => 588, 'ratio' => false, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('PLANO_' . $i . '_FOTO', 'NO SE ENCONTRÓ EL PLANO');
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
                                            CONCAT("Certificado equipo - ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                            equipos_documentos.RUTA_DOCUMENTO AS archivo
                                        FROM
                                            reporteequiposutilizados
                                        LEFT JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                        LEFT JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
                                        WHERE
                                            reporteequiposutilizados.proyecto_id = ' . $proyecto_id . '
                                            AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                            AND equipos_documentos.DOCUMENTO_TIPO = 4
                                            AND equipos_documentos.RUTA_DOCUMENTO <> ""
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
                                            AND reporteanexos.agente_nombre = "' . $agente_nombre . '"
                                    )
                                ) AS ANEXO');

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL


            $informe_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';


            // GUARDAR WORD FINAL
            $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word


            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                Storage::delete('reportes/informes/Informe_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
            }


            /*
            //================================================================================
            // CREAR .ZIP


            // Define Dir Folder
            $zip_ruta = storage_path('app/reportes/informes');
            
            // Zip File Name
            $zip_nombre = 'Informe de '.$agente_nombre.' - '.$proyecto->proyecto_folio.' + Anexos.zip';
            
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
            $word_ruta = storage_path('app/reportes/informes/'.$informe_nombre);
            // $plantillaword->saveAs($word_ruta); //GUARDAR Y CREAR archivo word TEMPORAL
            return response()->download($word_ruta)->deleteFileAfterSend(true);
            */



            //--------------------------------------------------------------------------------


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
                                                AND reporterevisiones.agente_id = 2 -- Vibracion
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
    public function reportevibracionworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Vibración';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $revision = reporterevisionesModel::findOrFail($revision_id);

            $zip_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';


            if (Storage::exists('reportes/informes/' . $zip_nombre)) {
                return response()->download(storage_path('app/reportes/informes/' . $zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
            } else {
                return '<h3>No se encontró el informe de ' . $agente_nombre . ', intentelo de nuevo</h3>';
            }


            //--------------------------------------

            // $informe_nombre = 'Informe - 1.4 Evaluación de Vibraciones - Proyecto '.$proyecto->proyecto_folio.' Rev-'.$revision->reporterevisiones_revision.'.docx';
            // // return response()->download(storage_path('app/reportes/informes/'.$informe_nombre))->deleteFileAfterSend(true);
            // return response()->download(storage_path('app/reportes/informes/'.$informe_nombre), $informe_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
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
                    return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . '</h3>';
                }
            } else {
                return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . '</h3>';
            }
        }
    }
}
