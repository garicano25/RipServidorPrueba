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

// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\clientes\clienteModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

//Revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;

// Modelos estructura reporte
use App\modelos\reportes\reporteairecatalogoModel;
use App\modelos\reportes\reporteaireModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteairecategoriaModel;
use App\modelos\reportes\reporteaireareaModel;
use App\modelos\reportes\reporteaireareacategoriaModel;
//----------------------------------------------------------
use App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel;
use App\modelos\reportes\reporteaireevaluacionModel;
use App\modelos\reportes\reporteaireevaluacioncategoriasModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\reportenotasModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;

class reporteairewordController extends Controller
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
            $texto = str_replace('Pemex Exploración y Producción', $recsensorial->recsensorial_empresa, $texto);
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
    public function reporteaireword(Request $request)
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

            $agente_id = 8;
            $agente_nombre = "Ventilación y calidad del aire";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reporteaireModel::where('proyecto_id', $proyecto_id)->get();
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
                $reporte  = reporteaireModel::findOrFail($reporteregistro_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe llenar los datos para poder generarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_aire_sin_MEL.docx')); //Ruta carpeta storage
            } else {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_aire_sin_MEL.docx')); //Ruta carpeta storage
            }


            // PORTADA EXTERNA
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 8) // ruido
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

            $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteaire_fecha;
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
                $cancelado_texto = '<w:br/>INFORME REVISIÓN ' . $revision->reporterevisiones_revision . ' CANCELADA';
            }

            if (($revision->reporterevisiones_revision + 0) > 0) {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
            } else {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
            }


            ##### INTRODUCCION ###################

            $introduccionTexto = $agente[0]->reporteaire_introduccion;
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


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_objetivoespecifico));


            // METODOLOGIA
            //================================================================================


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_metodologia_4_2));


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_ubicacioninstalacion));


            // Imagen FOTO
            if ($reporte->reporteaire_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reporte->reporteaire_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporte->reporteaire_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            // PROCESO INSTALACION
            //================================================================================


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $reporte->reporteaire_procesoinstalacion))));


            // TABLA 5.3 Población ocupacionalmente expuesta
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Poppins';
                $font_size = 10;
            } else {
                $fuente = 'Poppins';
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


            // $areas_poe = 0;


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reporteairearea_instalacion,
                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        reportearea.reporteairearea_porcientooperacion,
                                        reportearea.reportearea_ventilacionsistema AS reporteairearea_ventilacionsistema,
                                        reportearea.reportearea_ventilacioncaracteristica AS reporteairearea_ventilacioncaracteristica,
                                        reportearea.reportearea_ventilacioncantidad AS reporteairearea_ventilacioncantidad,
                                        reporteareacategoria.reportecategoria_id AS reporteairecategoria_id,
                                        reportecategoria.reportecategoria_orden AS reporteairecategoria_orden,
                                        reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reporteaireareacategoria.reporteairecategoria_id, "activo", "") AS checked
                                            FROM
                                                reporteaireareacategoria
                                            WHERE
                                                reporteaireareacategoria.reporteairearea_id = reportearea.id
                                                AND reporteaireareacategoria.reporteairecategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reporteaireareacategoria.reporteaireareacategoria_poe = ' . $reporteregistro_id . ' 
                                            LIMIT 1
                                        ), "") AS activo,
                                        reporteareacategoria.reporteareacategoria_total AS reporteairecategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh AS reporteaireareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades AS reporteaireareacategoria_actividades,
                                        (
                                            SELECT
                                                reporteaire.reporteaire_actividadprincipal 
                                            FROM
                                                reporteaire
                                            WHERE
                                                reporteaire.id = ' . $reporteregistro_id . ' 
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
                                        reporteairearea.id,
                                        reporteairearea.proyecto_id,
                                        reporteairearea.registro_id,
                                        REPLACE(reporteairearea.reporteairearea_instalacion, "\"", "") AS reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteairearea.reporteairearea_porcientooperacion,
                                        reporteaireareacategoria.reporteairecategoria_id,
                                        reporteairecategoria.reporteairecategoria_nombre,
                                        reporteairecategoria.reporteairecategoria_total,
                                        (
                                            SELECT
                                                reporteaire.reporteaire_actividadprincipal 
                                            FROM
                                                reporteaire
                                            WHERE
                                                reporteaire.id = reporteairearea.registro_id
                                        ) AS actividad_principal_instalacion
                                    FROM
                                        reporteairearea
                                        LEFT OUTER JOIN reporteaireareacategoria ON reporteairearea.id = reporteaireareacategoria.reporteairearea_id
                                        LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id
                                    WHERE
                                        reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                    ORDER BY
                                        reporteairearea.reporteairearea_numorden ASC,
                                        reporteairearea.reporteairearea_nombre ASC');
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
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteairearea_instalacion) {
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
                                                        IFNULL(SUM( TABLA.reporteairecategoria_total ), 0) AS total 
                                                    FROM
                                                        (
                                                            SELECT
                                                                reporteairecategoria.proyecto_id,
                                                                reporteairecategoria.registro_id,
                                                                reporteaireareacategoria.reporteaireareacategoria_poe,
                                                                reporteairearea.reporteairearea_instalacion,
                                                                reporteairecategoria.reporteairecategoria_nombre,
                                                                reporteairecategoria.reporteairecategoria_total 
                                                            FROM
                                                                reporteaireareacategoria
                                                                LEFT JOIN reporteairearea ON reporteaireareacategoria.reporteairearea_id = reporteairearea.id
                                                                LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id 
                                                            WHERE
                                                                reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                                                AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                                                AND reporteaireareacategoria.reporteaireareacategoria_poe = 0
                                                                AND REPLACE(reporteairearea.reporteairearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                            GROUP BY
                                                                reporteairecategoria.proyecto_id,
                                                                reporteairecategoria.registro_id,
                                                                reporteaireareacategoria.reporteaireareacategoria_poe,
                                                                reporteairearea.reporteairearea_instalacion,
                                                                reporteairecategoria.reporteairecategoria_nombre,
                                                                reporteairecategoria.reporteairecategoria_total 
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
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reporteairearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteairearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteairearea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                //     $instalacion = $value->reporteairearea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteairecategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteairecategoria_total, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->actividad_principal_instalacion), $texto);

                    $instalacion = $value->reporteairearea_instalacion;
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
                                            IFNULL(SUM( TABLA.reporteairecategoria_total ), 0) AS total 
                                        FROM
                                            (
                                                SELECT
                                                    reporteairecategoria.proyecto_id,
                                                    reporteairecategoria.registro_id,
                                                    reporteaireareacategoria.reporteaireareacategoria_poe,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    reporteairecategoria.reporteairecategoria_nombre,
                                                    reporteairecategoria.reporteairecategoria_total 
                                                FROM
                                                    reporteaireareacategoria
                                                    LEFT JOIN reporteairearea ON reporteaireareacategoria.reporteairearea_id = reporteairearea.id
                                                    LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id 
                                                WHERE
                                                    reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reporteaireareacategoria.reporteaireareacategoria_poe = 0
                                                    AND REPLACE(reporteairearea.reporteairearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                GROUP BY
                                                    reporteairecategoria.proyecto_id,
                                                    reporteairecategoria.registro_id,
                                                    reporteaireareacategoria.reporteaireareacategoria_poe,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    reporteairecategoria.reporteairecategoria_nombre,
                                                    reporteairecategoria.reporteairecategoria_total 
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


            // $areas_poe = 0;


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reporteairearea_instalacion,
                                        TABLA.reporteairearea_nombre,
                                        TABLA.reporteairearea_numorden,
                                        TABLA.reporteairearea_porcientooperacion,
                                        TABLA.reporteairearea_ventilacionsistema,
                                        TABLA.reporteairearea_ventilacioncaracteristica,
                                        TABLA.reporteairearea_ventilacioncantidad,
                                        TABLA.reporteairecategoria_id,
                                        TABLA.reporteairecategoria_orden,
                                        TABLA.reporteairecategoria_nombre,
                                        TABLA.activo,
                                        TABLA.reporteairecategoria_total,
                                        TABLA.reporteaireareacategoria_geh,
                                        TABLA.reporteaireareacategoria_actividades,
                                        TABLA.actividad_principal_instalacion
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                reportearea.reportearea_orden AS reporteairearea_numorden,
                                                reportearea.reportearea_porcientooperacion,
                                                reportearea.reporteairearea_porcientooperacion,
                                                reportearea.reportearea_ventilacionsistema AS reporteairearea_ventilacionsistema,
                                                reportearea.reportearea_ventilacioncaracteristica AS reporteairearea_ventilacioncaracteristica,
                                                reportearea.reportearea_ventilacioncantidad AS reporteairearea_ventilacioncantidad,
                                                reporteareacategoria.reportecategoria_id AS reporteairecategoria_id,
                                                reportecategoria.reportecategoria_orden AS reporteairecategoria_orden,
                                                reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteaireareacategoria.reporteairecategoria_id, "activo", "") AS checked
                                                    FROM
                                                        reporteaireareacategoria
                                                    WHERE
                                                        reporteaireareacategoria.reporteairearea_id = reportearea.id
                                                        AND reporteaireareacategoria.reporteairecategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteaireareacategoria.reporteaireareacategoria_poe = ' . $reporteregistro_id . ' 
                                                    LIMIT 1
                                                ), "") AS activo,
                                                reporteareacategoria.reporteareacategoria_total AS reporteairecategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS reporteaireareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades AS reporteaireareacategoria_actividades,
                                                (
                                                    SELECT
                                                        reporteaire.reporteaire_actividadprincipal 
                                                    FROM
                                                        reporteaire
                                                    WHERE
                                                        reporteaire.id = ' . $reporteregistro_id . ' 
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
                                        TABLA.reporteairearea_porcientooperacion > 0
                                        AND TABLA.activo != ""
                                    ORDER BY
                                        TABLA.reporteairearea_numorden ASC,
                                        TABLA.reporteairearea_nombre ASC,
                                        TABLA.reporteairecategoria_orden ASC,
                                        TABLA.reporteairecategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteairearea.proyecto_id,
                                        reporteairearea.registro_id,
                                        reporteaireareacategoria.id,
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteaireareacategoria.reporteairearea_id,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteaireareacategoria.reporteairecategoria_id,
                                        reporteairecategoria.reporteairecategoria_nombre,
                                        reporteaireareacategoria.reporteaireareacategoria_actividades
                                    FROM
                                        reporteaireareacategoria
                                        LEFT JOIN reporteairearea ON reporteaireareacategoria.reporteairearea_id = reporteairearea.id
                                        LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id
                                    WHERE
                                        reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteairearea.reporteairearea_porcientooperacion > 0 
                                        AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                    ORDER BY
                                        reporteairearea.reporteairearea_numorden ASC,
                                        reporteairearea.reporteairearea_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 1500;
            $ancho_col_4 = 3500;
            $ancho_col_5 = 4000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


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
                if ($instalacion != $value->reporteairearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reporteairearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteairearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteairearea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                //     $instalacion = $value->reporteairearea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteairecategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteaireareacategoria_actividades, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_4', $table);


            // TABLA 5.5 Identificación de las áreas
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        reportearea.reporteairearea_porcientooperacion,
                                        reportearea.reportearea_ventilacionsistema AS reporteairearea_ventilacionsistema,
                                        reportearea.reportearea_ventilacioncaracteristica AS reporteairearea_ventilacioncaracteristica,
                                        reportearea.reportearea_ventilacioncantidad AS reporteairearea_ventilacioncantidad
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportearea.reporteairearea_porcientooperacion > 0 
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteairearea.proyecto_id,
                                        reporteairearea.registro_id,
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteairearea.reporteairearea_porcientooperacion,
                                        reporteairearea.reporteairearea_ventilacionsistema,
                                        reporteairearea.reporteairearea_ventilacioncaracteristica,
                                        reporteairearea.reporteairearea_ventilacioncantidad 
                                    FROM
                                        reporteairearea
                                    WHERE
                                        reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteairearea.reporteairearea_porcientooperacion > 0
                                    ORDER BY
                                        reporteairearea.reporteairearea_numorden,
                                        reporteairearea.reporteairearea_nombre');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 2000;
            $ancho_col_4 = 3500;
            $ancho_col_5 = 2500;
            $ancho_col_6 = 1000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de ventilación /<w:br/>Tipo de aire acondicionado', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Características', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);


            $numero_fila = 0;
            $instalacion = 'xxxx';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteairearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de ventilación /<w:br/>Tipo de aire acondicionado', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Características', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reporteairearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteairearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteairearea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                //     $instalacion = $value->reporteairearea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteairearea_ventilacionsistema, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteairearea_ventilacioncaracteristica, $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reporteairearea_ventilacioncantidad, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_5', $table);


            // TABLA 6.1 Condiciones de operación durante la evaluación (representado en porcentaje)
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        reportearea.reporteairearea_porcientooperacion,
                                        reportearea.reportearea_ventilacionsistema AS reporteairearea_ventilacionsistema,
                                        reportearea.reportearea_ventilacioncaracteristica AS reporteairearea_ventilacioncaracteristica,
                                        reportearea.reportearea_ventilacioncantidad AS reporteairearea_ventilacioncantidad
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportearea.reporteairearea_porcientooperacion > 0 
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteairearea.proyecto_id,
                                        reporteairearea.registro_id,
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_porcientooperacion 
                                    FROM
                                        reporteairearea
                                    WHERE
                                        reporteairearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteairearea.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteairearea.reporteairearea_porcientooperacion > 0
                                    ORDER BY
                                        reporteairearea.reporteairearea_numorden ASC,
                                        reporteairearea.reporteairearea_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 5500;
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


            $instalacion = 'xxxx';
            $total = 'XXXX';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteairearea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reporteairearea_instalacion;
                    $numero_fila = 0;
                }


                $numero_fila += 1;
                $table->addRow(); //fila


                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);


                // if($instalacion != $value->reporteairearea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                //     $instalacion = $value->reporteairearea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);



                if ($instalacion != $value->reporteairearea_instalacion || $total != $value->reporteairearea_porcientooperacion) {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_porcientooperacion . '%', $texto);


                    $instalacion = $value->reporteairearea_instalacion;
                    $total = $value->reporteairearea_porcientooperacion;
                } else {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_6_1', $table);


            // TABLA 7.1 Resultados de bioaerosoles
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Poppins';
                $font_size = 9;
            } else {
                $fuente = 'Poppins';
                $font_size = 9;
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


            // $areas_poe = 0;


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaireevaluacion.id,
                                        reporteaireevaluacion.proyecto_id,
                                        reporteaireevaluacion.registro_id,
                                        reporteaireevaluacion.reporteairearea_id,
                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                        (
                                            SELECT
                                                -- TABLA.proyecto_id,
                                                -- TABLA.registro_id,
                                                -- TABLA.reporteairearea_id,
                                                COUNT(TABLA.reporteaireevaluacion_punto)
                                            FROM
                                                reporteaireevaluacion AS TABLA
                                            WHERE
                                                TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                        ) AS total_puntosarea,
                                        (
                                            SELECT
                                                -- TABLA.proyecto_id,
                                                -- TABLA.registro_id,
                                                -- TABLA.reporteairearea_id,
                                                COUNT(TABLA.reporteaireevaluacion_punto)
                                            FROM
                                                reporteaireevaluacion AS TABLA
                                            WHERE
                                                TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                AND TABLA.reporteaireevaluacion_ubicacion = reporteaireevaluacion.reporteaireevaluacion_ubicacion
                                        ) AS total_puntosubicacion,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS ct_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS ctma_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS hongos_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS levaduras_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                        (
                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, "Dentro de norma", "Fuera de norma")
                                        ) AS temperatura_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                        (
                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, "Dentro de norma", "Fuera de norma")
                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), "Dentro de norma", "Fuera de norma")
                                        ) AS velocidad_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                        (
                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, "Dentro de norma", "Fuera de norma")
                                        ) AS humedad_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS co_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co2,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS co2_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_so2,
                                                (
                                                IF(
                                                    -- Verificar si el valor contiene solo letras o es N.D, N.A, N/A
                                                    reporteaireevaluacion.reporteaireevaluacion_so2 REGEXP "^[A-Za-z]+$|^N[./]?D$|^N[./]?A$", 
                                                    -- Si contiene solo letras o las abreviaturas, retornamos "Dentro de norma"
                                                    "Dentro de norma",  
                                                    -- Si contiene números, continuamos con la limpieza
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_so2, ">" , ""), "<" ,""), " ", ""), DECIMAL(10,2)) >= 0,
                                                        -- Después de limpiar, verificamos si el valor es mayor o igual a 0.25
                                                        IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_so2, ">" , ""), "<" ,""), " ", "") + 0) > 0.25,
                                                            "Fuera de norma",  -- Si es mayor a 0.25, está fuera de norma
                                                            "Dentro de norma"  -- Si es menor, está dentro de norma
                                                        ),
                                                        "Fuera de norma"  -- Si no es un número válido o es negativo, es fuera de norma
                                                    )
                                                )
                                            ) AS so2_resultado  
                                    FROM
                                        reporteaireevaluacion
                                        LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                    WHERE
                                        reporteaireevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                    ORDER BY
                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                        reportearea.reportearea_orden ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteaireevaluacion.id,
                                        reporteaireevaluacion.proyecto_id,
                                        reporteaireevaluacion.registro_id,
                                        reporteaireevaluacion.reporteairearea_id,
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                        (
                                            SELECT
                                                -- TABLA.proyecto_id,
                                                -- TABLA.registro_id,
                                                -- TABLA.reporteairearea_id,
                                                COUNT(TABLA.reporteaireevaluacion_punto)
                                            FROM
                                                reporteaireevaluacion AS TABLA
                                            WHERE
                                                TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                        ) AS total_puntosarea,
                                        (
                                            SELECT
                                                -- TABLA.proyecto_id,
                                                -- TABLA.registro_id,
                                                -- TABLA.reporteairearea_id,
                                                COUNT(TABLA.reporteaireevaluacion_punto)
                                            FROM
                                                reporteaireevaluacion AS TABLA
                                            WHERE
                                                TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                AND TABLA.reporteaireevaluacion_ubicacion = reporteaireevaluacion.reporteaireevaluacion_ubicacion
                                        ) AS total_puntosubicacion,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS ct_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS ctma_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS hongos_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS levaduras_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                        (
                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, "Dentro de norma", "Fuera de norma")
                                        ) AS temperatura_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                        (
                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, "Dentro de norma", "Fuera de norma")
                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), "Dentro de norma", "Fuera de norma")
                                        ) AS velocidad_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                        (
                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, "Dentro de norma", "Fuera de norma")
                                        ) AS humedad_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS co_resultado,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co2,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                        (
                                            IF(
                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                , IF(
                                                    (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                    , "Dentro de norma"
                                                    , "Fuera de norma"
                                                )
                                                , "Fuera de norma"
                                            )
                                        ) AS co2_resultado,
                                        reporteaireevaluacion.reporteaireevaluacion_so2,
                                                (
                                                IF(
                                                    -- Verificar si el valor contiene solo letras o es N.D, N.A, N/A
                                                    reporteaireevaluacion.reporteaireevaluacion_so2 REGEXP "^[A-Za-z]+$|^N[./]?D$|^N[./]?A$", 
                                                    -- Si contiene solo letras o las abreviaturas, retornamos "Dentro de norma"
                                                    "Dentro de norma",  
                                                    -- Si contiene números, continuamos con la limpieza
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_so2, ">" , ""), "<" ,""), " ", ""), DECIMAL(10,2)) >= 0,
                                                        -- Después de limpiar, verificamos si el valor es mayor o igual a 0.25
                                                        IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_so2, ">" , ""), "<" ,""), " ", "") + 0) > 0.25,
                                                            "Fuera de norma",  -- Si es mayor a 0.25, está fuera de norma
                                                            "Dentro de norma"  -- Si es menor, está dentro de norma
                                                        ),
                                                        "Fuera de norma"  -- Si no es un número válido o es negativo, es fuera de norma
                                                    )
                                                )
                                            ) AS so2_resultado 
                                    FROM
                                        reporteaireevaluacion
                                        LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                    WHERE
                                        reporteaireevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                    ORDER BY
                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                        reporteairearea.reporteairearea_numorden ASC');
            }


            // Columnas
            $ancho_col_1 = 800;
            $ancho_col_2 = 1300;
            $ancho_col_3 = 1300;
            $ancho_col_4 = 800;
            $ancho_col_5 = 2700;
            $ancho_col_6 = 1400;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1000;
            $ancho_col_9 = 1000;
            $ancho_col_10 = 1700;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // Encabezado
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Método', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Unidad', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Límite<w:br/>permisible', $encabezado_texto);
            $table->addCell($ancho_col_9, $encabezado_celda)->addTextRun($centrado)->addText('Resultado', $encabezado_texto);
            $table->addCell($ancho_col_10, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $total = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);

                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosarea, $texto);
                    $area = $value->reporteairearea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                    $table->addCell($ancho_col_4, $continua_fila);
                }

                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText('Cuenta de microorganismos coliformes totales en placa (CT)', $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('NOM-113-SSA-1994', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText('UFC/mtra', $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText('500', $texto);
                $table->addCell($ancho_col_9, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ct, $texto);
                if ($value->ct_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->ct_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->ct_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $continua_fila);
                $table->addCell($ancho_col_2, $continua_fila);
                $table->addCell($ancho_col_3, $continua_fila);
                $table->addCell($ancho_col_4, $continua_fila);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText('Cuenta Total de Mesofílicos Aerobios (CTMA)', $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('NOM-092-SSA1-1994', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText('UFC/mtra', $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText('500', $texto);
                $table->addCell($ancho_col_9, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ctma, $texto);
                if ($value->ctma_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->ctma_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->ctma_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $continua_fila);
                $table->addCell($ancho_col_2, $continua_fila);
                $table->addCell($ancho_col_3, $continua_fila);
                $table->addCell($ancho_col_4, $continua_fila);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText('Hongos', $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('NOM-111-SSA1-1994', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText('UFC/mtra', $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText('500', $texto);
                $table->addCell($ancho_col_9, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_hongos, $texto);
                if ($value->hongos_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->hongos_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->hongos_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $continua_fila);
                $table->addCell($ancho_col_2, $continua_fila);
                $table->addCell($ancho_col_3, $continua_fila);
                $table->addCell($ancho_col_4, $continua_fila);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText('Levaduras', $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('NOM-111-SSA1-1994', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText('UFC/mtra', $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText('500', $texto);
                $table->addCell($ancho_col_9, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_levaduras, $texto);
                if ($value->levaduras_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->levaduras_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_10, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->levaduras_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_1', $table);


            // TABLA 7.2 Temperatura del aire
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en °C', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en °C', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('22-24.5', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_temperatura, $texto);


                if ($value->temperatura_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->temperatura_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->temperatura_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_2', $table);


            // TABLA 7.3 Velocidad del aire
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en m/s', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en m/s', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_velocidadlimite, $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_velocidad, $texto);


                if ($value->velocidad_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->velocidad_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->velocidad_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_3', $table);


            // TABLA 7.4 Humedad relativa
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en %', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en %', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('20-60', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_humedad, $texto);


                if ($value->humedad_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->humedad_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->humedad_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_4', $table);


            // TABLA 7.5 Monóxido de Carbono (CO)
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('25', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_co, $texto);


                if ($value->co_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->co_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->co_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_5', $table);


            // TABLA 7.6 Dióxido de Carbono (CO2)
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('5000', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_co2, $texto);


                if ($value->co2_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->co2_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->co2_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_6', $table);


            // TABLA 7.7 Dióxido de Aarbono (SO2)
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 1000;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 2800;
            $ancho_col_4 = 2400;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 1600;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1700;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Total, de<w:br/>puntos', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Resultado<w:br/>en ppm', $encabezado_texto);
            $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $instalacion = 'XXXXX';
            $area = 'XXXXX';
            $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_punto, $texto);


                if ($instalacion != $value->reporteairearea_instalacion) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_instalacion, $texto);
                    $instalacion = $value->reporteairearea_instalacion;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if ($area != $value->reporteairearea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteairearea_nombre, $texto);
                    $area = $value->reporteairearea_nombre;

                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaireevaluacion_ubicacion;

                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);

                    if ($ubicacion != $value->reporteaireevaluacion_ubicacion) {
                        $table->addCell($ancho_col_4,
                            $combinar_fila
                        )->addTextRun($centrado)->addText($value->reporteaireevaluacion_ubicacion, $texto);
                        $ubicacion = $value->reporteaireevaluacion_ubicacion;

                        $table->addCell($ancho_col_5,
                            $combinar_fila
                        )->addTextRun($centrado)->addText($value->total_puntosubicacion, $texto);
                    } else {
                        $table->addCell($ancho_col_4,
                            $continua_fila
                        );
                        $table->addCell($ancho_col_5,
                            $continua_fila
                        );
                    }
                }


                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText('0.25', $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaireevaluacion_so2, $texto);


                if ($value->co2_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->so2_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => "Poppins"));
                } else {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_8, array('bgColor' => $bgColor, 'valign' => 'center'))->addTextRun($centrado)->addText($value->so2_resultado, array('color' => $text_color, 'size' => $font_size, 'bold' => true, 'name' => "Poppins"));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_7', $table);


            // TABLA 7.7 Matriz de exposición laboral
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Poppins';
                $font_size = 6;
            } else {
                $fuente = 'Poppins';
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


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaireevaluacion.proyecto_id,
                                        reporteaireevaluacion.registro_id,
                                        reporteaireevaluacion.id,
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
                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                        reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                        reportecategoria.reportecategoria_total AS reporteairecategoria_total,
                                        (
                                            SELECT
                                                COUNT(reporteaireevaluacioncategorias.reporteairecategoria_id)
                                            FROM
                                                reporteaireevaluacioncategorias 
                                            WHERE
                                                reporteaireevaluacioncategorias.reporteaireevaluacion_id = reporteaireevaluacion.id
                                        ) AS total_por_punto,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo,
                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                        -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                        -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                        -- reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                        -- reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                        -- reporteaireevaluacion.reporteaireevaluacion_humedad,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co2 
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2 
                                    FROM
                                        reporteaireevaluacion
                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                        LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                        LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                    WHERE
                                        reporteaireevaluacion.proyecto_id = ' . $proyecto_id . '  
                                        AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        AND reportecategoria.reportecategoria_nombre != "" 
                                    ORDER BY
                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                        reportearea.reportearea_orden ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteaireevaluacion.proyecto_id,
                                        reporteaireevaluacion.registro_id,
                                        reporteaireevaluacion.id,
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
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                        reporteairecategoria.reporteairecategoria_nombre,
                                        reporteairecategoria.reporteairecategoria_total,
                                        (
                                            SELECT
                                                COUNT(reporteaireevaluacioncategorias.reporteairecategoria_id)
                                            FROM
                                                reporteaireevaluacioncategorias 
                                            WHERE
                                                reporteaireevaluacioncategorias.reporteaireevaluacion_id = reporteaireevaluacion.id
                                        ) AS total_por_punto,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo,
                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                        -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                        -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                        -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                        -- reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                        -- reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                        -- reporteaireevaluacion.reporteaireevaluacion_humedad,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co,
                                        -- reporteaireevaluacion.reporteaireevaluacion_co2 
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                        REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2 
                                    FROM
                                        reporteaireevaluacion
                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                        LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                        LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                    WHERE
                                        reporteaireevaluacion.proyecto_id = ' . $proyecto_id . '  
                                        AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteairecategoria.reporteairecategoria_nombre != "" 
                                    ORDER BY
                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                        reporteairearea.reporteairearea_numorden ASC');
            }


            if (($proyecto->catregion_id + 0) == 1) //REGION NORTE
            {
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 500;
                $ancho_col_2 = 1000;
                $ancho_col_3 = 1000;
                $ancho_col_4 = 1000;
                $ancho_col_5 = 1000;
                $ancho_col_6 = 1000;
                $ancho_col_7 = 500;
                $ancho_col_8 = 1500;
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

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Ventilación y Calidad del Aire', $encabezado_texto3);

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
                $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Temperatura (°C)', $encabezado_texto3);
                $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Velocidad del aire (m/s)', $encabezado_texto3);
                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Humedad relativa (%)', $encabezado_texto3);
                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_15, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO2 (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_16, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CT (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_17, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CTMA (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_18, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Hongos (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_19, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Levaduras (UFC)', $encabezado_texto3);


                $punto = 'XXXX';
                $subdir = 'XXXX';
                $activo = 'XXXX';
                $instalacion = 'XXXX';
                $area = 'XXXX';
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila


                    if ($punto != $value->reporteaireevaluacion_punto) {
                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_punto, $texto3);
                        // $punto = $value->reporteaireevaluacion_punto;
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


                    if ($instalacion != $value->reporteairearea_instalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteairearea_instalacion, $texto3);
                        $instalacion = $value->reporteairearea_instalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->reporteairearea_nombre) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteairearea_nombre, $texto3);
                        $area = $value->reporteairearea_nombre;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteairecategoria_nombre, $texto3);


                    if ($punto != $value->reporteaireevaluacion_punto) {
                        $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->total_por_punto, $texto3);
                        $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_geo, $texto3);

                        $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_temperatura, $texto3);
                        $table->addCell($ancho_col_12, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_velocidad, $texto3);
                        $table->addCell($ancho_col_13, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_humedad, $texto3);
                        $table->addCell($ancho_col_14, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co, $texto3);
                        $table->addCell($ancho_col_15, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co2, $texto3);
                        $table->addCell($ancho_col_16, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_ct, $texto3);
                        $table->addCell($ancho_col_17, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_ctma, $texto3);
                        $table->addCell($ancho_col_18, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_hongos, $texto3);
                        $table->addCell($ancho_col_19, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_levaduras, $texto3);

                        $punto = $value->reporteaireevaluacion_punto;
                    } else {
                        $table->addCell($ancho_col_9, $continua_fila3);
                        $table->addCell($ancho_col_10, $continua_fila3);

                        $table->addCell($ancho_col_11, $continua_fila3);
                        $table->addCell($ancho_col_12, $continua_fila3);
                        $table->addCell($ancho_col_13, $continua_fila3);
                        $table->addCell($ancho_col_14, $continua_fila3);
                        $table->addCell($ancho_col_15, $continua_fila3);
                        $table->addCell($ancho_col_16, $continua_fila3);
                        $table->addCell($ancho_col_17, $continua_fila3);
                        $table->addCell($ancho_col_18, $continua_fila3);
                        $table->addCell($ancho_col_19, $continua_fila3);
                    }
                }
            } else if (($proyecto->catregion_id + 0) == 2) //SUR
            {
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 500;
                $ancho_col_2 = 1000;
                $ancho_col_3 = 1000;
                $ancho_col_4 = 1000;
                $ancho_col_5 = 1500;
                $ancho_col_6 = 1000;
                $ancho_col_7 = 500;
                $ancho_col_8 = 2000;
                $ancho_col_9 = 500;
                $ancho_col_10 = 500;
                $ancho_col_11 = 500;
                $ancho_col_12 = 500;
                $ancho_col_13 = 500;
                $ancho_col_14 = 500;
                $ancho_col_15 = 500;
                $ancho_col_16 = 500;
                $ancho_col_17 = 500;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Ventilación y Calidad del Aire', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Temperatura (°C)', $encabezado_texto3);
                $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Velocidad del aire (m/s)', $encabezado_texto3);
                $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Humedad relativa (%)', $encabezado_texto3);
                $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO2 (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_14, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CT (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_15, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CTMA (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_16, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Hongos (UFC)', $encabezado_texto3);
                $table->addCell($ancho_col_17, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Levaduras (UFC)', $encabezado_texto3);


                $punto = 'XXXX';
                $subdir = 'XXXX';
                $activo = 'XXXX';
                $instalacion = 'XXXX';
                $area = 'XXXX';
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila


                    if ($punto != $value->reporteaireevaluacion_punto) {
                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_punto, $texto3);
                        // $punto = $value->reporteaireevaluacion_punto;
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


                    if ($instalacion != $value->reporteairearea_instalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteairearea_instalacion, $texto3);
                        $instalacion = $value->reporteairearea_instalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->reporteairearea_nombre) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteairearea_nombre, $texto3);
                        $area = $value->reporteairearea_nombre;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteairecategoria_nombre, $texto3);


                    if ($punto != $value->reporteaireevaluacion_punto) {
                        $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_temperatura, $texto3);
                        $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_velocidad, $texto3);
                        $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_humedad, $texto3);
                        $table->addCell($ancho_col_12, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co, $texto3);
                        $table->addCell($ancho_col_13, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co2, $texto3);
                        $table->addCell($ancho_col_14, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_ct, $texto3);
                        $table->addCell($ancho_col_15, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_ctma, $texto3);
                        $table->addCell($ancho_col_16, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_hongos, $texto3);
                        $table->addCell($ancho_col_17, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_levaduras, $texto3);

                        $punto = $value->reporteaireevaluacion_punto;
                    } else {
                        $table->addCell($ancho_col_9, $continua_fila3);
                        $table->addCell($ancho_col_10, $continua_fila3);
                        $table->addCell($ancho_col_11, $continua_fila3);
                        $table->addCell($ancho_col_12, $continua_fila3);
                        $table->addCell($ancho_col_13, $continua_fila3);
                        $table->addCell($ancho_col_14, $continua_fila3);
                        $table->addCell($ancho_col_15, $continua_fila3);
                        $table->addCell($ancho_col_16, $continua_fila3);
                        $table->addCell($ancho_col_17, $continua_fila3);
                    }
                }
            } else //MARINA
            {
                if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                {
                    $categorias = DB::select('SELECT
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                    -- reportearea.reportearea_nombre,
                                                    -- reportearea.reportearea_orden,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_punto
                                                FROM
                                                    reporteaireevaluacion
                                                    LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                    INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                    LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                                WHERE
                                                    reporteaireevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reportecategoria.reportecategoria_nombre != "" 
                                                GROUP BY
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reportecategoria.reportecategoria_nombre
                                                ORDER BY
                                                    reportearea.reportearea_instalacion ASC,
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC');
                } else {
                    $categorias = DB::select('SELECT
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    -- reporteairearea.reporteairearea_nombre,
                                                    -- reporteairearea.reporteairearea_numorden,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reporteairecategoria.reporteairecategoria_nombre
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_punto
                                                FROM
                                                    reporteaireevaluacion
                                                    LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                    INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                    LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                                WHERE
                                                    reporteaireevaluacion.proyecto_id = ' . $proyecto_id . '  
                                                    AND reporteaireevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reporteairecategoria.reporteairecategoria_nombre != "" 
                                                GROUP BY
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reporteairecategoria.reporteairecategoria_nombre
                                                ORDER BY
                                                    reporteairearea.reporteairearea_instalacion ASC,
                                                    reporteairecategoria.reporteairecategoria_nombre ASC');
                }


                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 500;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 1750;
                $ancho_col_6 = 1000;
                $ancho_col_7 = 1750;
                $ancho_col_8 = 500;
                $ancho_col_9 = 500;
                $ancho_col_10 = 500;
                $ancho_col_11 = 500;
                $ancho_col_12 = 500;
                $ancho_col_13 = 1500;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('laboral', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Ventilación y Calidad del Aire', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coordinación', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);

                $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Temperatura (°C)', $encabezado_texto3);
                $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Velocidad del aire (m/s)', $encabezado_texto3);
                $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Humedad relativa (%)', $encabezado_texto3);
                $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('CO2 (ppm)', $encabezado_texto3);
                $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Bioaerosoles "CT, CTMA, hongos, levaduras" (UFC/mtra)', $encabezado_texto3);


                $fila = 0;
                $punto = 'XXXX';
                $subdir = 'XXXX';
                $activo = 'XXXX';
                $instalacion = 'XXXX';
                $area = 'XXXX';
                foreach ($categorias as $key => $categoria) {
                    if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                    {
                        $datos = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.id,
                                                TABLA.catregion_nombre,
                                                TABLA.catsubdireccion_nombre,
                                                TABLA.catgerencia_nombre,
                                                TABLA.catactivo_nombre,
                                                TABLA.gerencia_activo,
                                                TABLA.reporteairearea_instalacion,
                                                TABLA.reporteairearea_nombre,
                                                TABLA.reporteairearea_numorden,
                                                TABLA.reporteaireevaluacion_ubicacion,
                                                TABLA.reporteairecategoria_id,
                                                TABLA.reporteairecategoria_nombre,
                                                TABLA.reporteaireevaluacioncategorias_nombre,
                                                TABLA.reporteaireevaluacioncategorias_ficha,
                                                TABLA.reporteaireevaluacion_punto,
                                                -- TABLA.reporteaireevaluacion_ct,
                                                -- TABLA.reporteaireevaluacion_ctma,
                                                -- TABLA.reporteaireevaluacion_hongos,
                                                -- TABLA.reporteaireevaluacion_levaduras,
                                                -- TABLA.reporteaireevaluacion_temperatura,
                                                -- TABLA.reporteaireevaluacion_velocidad,
                                                -- TABLA.reporteaireevaluacion_humedad,
                                                -- TABLA.reporteaireevaluacion_co,
                                                -- TABLA.reporteaireevaluacion_co2,

                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2, 
                                                
                                                -- TABLA.ct_resultado,
                                                -- TABLA.ctma_resultado,
                                                -- TABLA.hongos_resultado,
                                                -- TABLA.levaduras_resultado,
                                                -- TABLA.temperatura_resultado,
                                                -- TABLA.velocidad_resultado,
                                                -- TABLA.humedad_resultado,
                                                -- TABLA.co_resultado,
                                                -- TABLA.co2_resultado,
                                                (
                                                    ROUND(ROUND((
                                                            TABLA.ct_resultado + 
                                                            TABLA.ctma_resultado + 
                                                            TABLA.hongos_resultado + 
                                                            TABLA.levaduras_resultado + 
                                                            TABLA.temperatura_resultado + 
                                                            TABLA.velocidad_resultado + 
                                                            TABLA.humedad_resultado + 
                                                            TABLA.co_resultado + 
                                                            TABLA.co2_resultado
                                                    ) / 9, 3) * 100, 1)
                                                ) AS cumplimiento 
                                            FROM
                                                (
                                                    SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteaireevaluacion.id,
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
                                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                        reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                                        reporteaireevaluacion.reporteaireevaluacion_ct,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                             IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ct_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ctma_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS hongos_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS levaduras_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                        ) AS temperatura_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                        (
                                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                        ) AS velocidad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                        ) AS humedad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , 1000), "<" ,""), " ", "") + 0) <= 25, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co2,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , 5000), "<" ,""), " ", "") + 0) <= 5000, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co2_resultado 
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                        LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                        LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = ' . $categoria->proyecto_id . ' 
                                                        AND reporteaireevaluacion.registro_id = ' . $categoria->registro_id . ' 
                                                        AND reporteaireevaluacioncategorias.reporteairecategoria_id = ' . $categoria->reporteairecategoria_id . '
                                                    ORDER BY
                                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                        reportecategoria.reportecategoria_nombre ASC
                                                ) AS TABLA
                                            ORDER BY
                                                cumplimiento ASC
                                            LIMIT 1');
                    } else {
                        $datos = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.id,
                                                TABLA.catregion_nombre,
                                                TABLA.catsubdireccion_nombre,
                                                TABLA.catgerencia_nombre,
                                                TABLA.catactivo_nombre,
                                                TABLA.gerencia_activo,
                                                TABLA.reporteairearea_instalacion,
                                                TABLA.reporteairearea_nombre,
                                                TABLA.reporteairearea_numorden,
                                                TABLA.reporteaireevaluacion_ubicacion,
                                                TABLA.reporteairecategoria_id,
                                                TABLA.reporteairecategoria_nombre,
                                                TABLA.reporteaireevaluacioncategorias_nombre,
                                                TABLA.reporteaireevaluacioncategorias_ficha,
                                                TABLA.reporteaireevaluacion_punto,
                                                -- TABLA.reporteaireevaluacion_ct,
                                                -- TABLA.reporteaireevaluacion_ctma,
                                                -- TABLA.reporteaireevaluacion_hongos,
                                                -- TABLA.reporteaireevaluacion_levaduras,
                                                -- TABLA.reporteaireevaluacion_temperatura,
                                                -- TABLA.reporteaireevaluacion_velocidad,
                                                -- TABLA.reporteaireevaluacion_humedad,
                                                -- TABLA.reporteaireevaluacion_co,
                                                -- TABLA.reporteaireevaluacion_co2,

                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2, 
                                                
                                                -- TABLA.ct_resultado,
                                                -- TABLA.ctma_resultado,
                                                -- TABLA.hongos_resultado,
                                                -- TABLA.levaduras_resultado,
                                                -- TABLA.temperatura_resultado,
                                                -- TABLA.velocidad_resultado,
                                                -- TABLA.humedad_resultado,
                                                -- TABLA.co_resultado,
                                                -- TABLA.co2_resultado,
                                                (
                                                    ROUND(ROUND((
                                                            TABLA.ct_resultado + 
                                                            TABLA.ctma_resultado + 
                                                            TABLA.hongos_resultado + 
                                                            TABLA.levaduras_resultado + 
                                                            TABLA.temperatura_resultado + 
                                                            TABLA.velocidad_resultado + 
                                                            TABLA.humedad_resultado + 
                                                            TABLA.co_resultado + 
                                                            TABLA.co2_resultado
                                                    ) / 9, 3) * 100, 1)
                                                ) AS cumplimiento 
                                            FROM
                                                (
                                                    SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteaireevaluacion.id,
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
                                                        reporteairearea.reporteairearea_instalacion,
                                                        reporteairearea.reporteairearea_nombre,
                                                        reporteairearea.reporteairearea_numorden,
                                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                        reporteairecategoria.reporteairecategoria_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                                        reporteaireevaluacion.reporteaireevaluacion_ct,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                             IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ct_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ctma_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS hongos_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS levaduras_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                        ) AS temperatura_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                        (
                                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                        ) AS velocidad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                        ) AS humedad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , 1000), "<" ,""), " ", "") + 0) <= 25, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co2,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , 5000), "<" ,""), " ", "") + 0) <= 5000, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co2_resultado 
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                        LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                        LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = ' . $categoria->proyecto_id . ' 
                                                        AND reporteaireevaluacion.registro_id = ' . $categoria->registro_id . ' 
                                                        AND reporteaireevaluacioncategorias.reporteairecategoria_id = ' . $categoria->reporteairecategoria_id . '
                                                    ORDER BY
                                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                        reporteairecategoria.reporteairecategoria_nombre ASC
                                                ) AS TABLA
                                            ORDER BY
                                                cumplimiento ASC
                                            LIMIT 1');
                    }


                    foreach ($datos as $key => $value) {
                        $table->addRow(); //fila


                        // if($punto != $value->reporteaireevaluacion_punto)
                        // {
                        $fila += 1;
                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($fila, $texto3);
                        // $punto = $value->reporteaireevaluacion_punto;
                        // }
                        // else
                        // {
                        //     $table->addCell($ancho_col_1, $continua_fila3);
                        // }


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


                        if ($instalacion != $value->reporteairearea_instalacion) {
                            $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteairearea_instalacion, $texto3);
                            $instalacion = $value->reporteairearea_instalacion;
                        } else {
                            $table->addCell($ancho_col_4, $continua_fila3);
                        }


                        $table->addCell($ancho_col_5, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_nombre, $texto3);
                        $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaireevaluacioncategorias_ficha, $texto3);
                        $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteairecategoria_nombre, $texto3);


                        $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_temperatura, $texto3);
                        $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_velocidad, $texto3);
                        $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_humedad, $texto3);
                        $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co, $texto3);
                        $table->addCell($ancho_col_12, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaireevaluacion_co2, $texto3);
                        $table->addCell($ancho_col_13, $combinar_fila3)->addTextRun($centrado3)->addText('CT: ' . $value->reporteaireevaluacion_ct . '<w:br/>CTMA: ' . $value->reporteaireevaluacion_ctma . '<w:br/>Hongos: ' . $value->reporteaireevaluacion_hongos . '<w:br/>Levaduras: ' . $value->reporteaireevaluacion_levaduras, $texto3);
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


            $plantillaword->setComplexBlock('TABLA_7_8', $table);


            // CONCLUSION
            //================================================================================


            $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteaire_conclusion));


            // DASHBOARD
            //================================================================================


            // $dashboard_ruta = storage_path('app/reportes/proyecto/'.$proyecto_id.'/'.$agente_nombre.'/'.$reporteregistro_id.'/dashboard/dashboard_grafica.jpg');


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
                                                                            AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
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
            if ($reporte->reporteaire_responsable1documento) {
                if (file_exists(storage_path('app/' . $reporte->reporteaire_responsable1documento))) {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reporteaire_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE1', $reporte->reporteaire_responsable1 . "<w:br/>" . $reporte->reporteaire_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporte->reporteaire_responsable2documento) {
                if (file_exists(storage_path('app/' . $reporte->reporteaire_responsable2documento))) {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reporteaire_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE2', $reporte->reporteaire_responsable2 . "<w:br/>" . $reporte->reporteaire_responsable2cargo);


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
                                                AND reporteplanoscarpetas.registro_id = ' . $reporteregistro_id . ' 
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%' . $agente_nombre . '%"');


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
                                                AND proyectoevidenciaplano.agente_nombre LIKE "%' . $carpeta->agente_nombre . '%" 
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = "' . $carpeta->reporteplanoscarpetas_nombre . '" 
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo ASC');

                    foreach ($planos as $key => $plano) {
                        $planoscarpetasvPoppinses .= '${PLANO_' . $planocontador . '_FOTO}';

                        $plano_archivo[] = $plano->proyectoevidenciaplano_archivo;

                        $planocontador += 1;
                    }
                }
            } else {
                $plano_archivo = array();
                $planoscarpetasvPoppinses = 'NO SE ENCONTRARÓN PLANOS QUE MOSTRAR.';
            }


            $plantillaword->setValue('PLANOS', $planoscarpetasvPoppinses);


            // TABLA ANEXO 3, EQUIPO UTILIZADO PARA LA MEDICION
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $sql = DB::select('SELECT DISTINCT
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


            // ANEXOS 6 Y 7  NOTAS ACLARATORIAS DE LA (STPS Y EMA)
            //================================================================================


            // $sql = DB::select('SELECT
            //                         reportenotas.id,
            //                         reportenotas.proyecto_id,
            //                         reportenotas.registro_id,
            //                         reportenotas.agente_id,
            //                         reportenotas.agente_nombre,
            //                         reportenotas.reportenotas_tipo,
            //                         reportenotas.reportenotas_descripcion 
            //                     FROM
            //                         reportenotas
            //                     WHERE
            //                         reportenotas.proyecto_id = '.$proyecto_id.' 
            //                         AND reportenotas.registro_id = '.$reporteregistro_id.' 
            //                         AND reportenotas.agente_id = 8
            //                     ORDER BY
            //                         reportenotas.reportenotas_tipo ASC,
            //                         reportenotas.id ASC');


            // // Crear tablas
            // $table = null;
            // $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
            // $table2 = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // encabezado tabla
            // $ancho_col_1 = 9500;

            // $total_stps = 0;
            // $total_ema = 0;

            // foreach ($sql as $key => $value)
            // {
            //     if (($value->reportenotas_tipo+0) == 1) // STPS
            //     {
            //         $total_stps += 1;

            //         $table->addRow(12300); //fila
            //         $table->addCell($ancho_col_1, array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' =>'ffffff', 'borderTopSize' => 1, 'borderRightColor' =>'ffffff', 'borderRightSize' => 1, 'borderBottomColor' =>'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' =>'ffffff', 'borderLeftSize' => 1))->addTextRun(array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15))->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Nota aclaratoria</w:t></w:r><w:r><w:t>:<w:br/><w:br/>'.$value->reportenotas_descripcion, array('color' => '000000', 'size' => 12, 'bold' => false, 'name' => $fuente));
            //     }
            //     else //EMA
            //     {
            //         $total_ema += 1;

            //         $table2->addRow(12300); //fila
            //         $table2->addCell($ancho_col_1, array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' =>'ffffff', 'borderTopSize' => 1, 'borderRightColor' =>'ffffff', 'borderRightSize' => 1, 'borderBottomColor' =>'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' =>'ffffff', 'borderLeftSize' => 1))->addTextRun(array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15))->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Nota aclaratoria</w:t></w:r><w:r><w:t>:<w:br/><w:br/>'.$value->reportenotas_descripcion, array('color' => '000000', 'size' => 12, 'bold' => false, 'name' => $fuente));
            //     }                
            // }


            // if ($total_stps > 0)
            // {
            //     $plantillaword->setComplexBlock('NOTA_ACLARATORIA_STPS', $table);
            // }
            // else
            // {
            //     $plantillaword->setValue('NOTA_ACLARATORIA_STPS', 'No se encontraron notas aclaratorías');
            // }


            // if ($total_ema > 0)
            // {
            //     $plantillaword->setComplexBlock('NOTA_ACLARATORIA_EMA', $table2);
            // }
            // else
            // {
            //     $plantillaword->setValue('NOTA_ACLARATORIA_EMA', 'No se encontraron notas aclaratorías');
            // }


            // $definiciones .= '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>'.$value->concepto.'</w:t></w:r><w:r><w:t>'.$value->descripcion.''.$dato['fuente_simbolo'].'<w:br/>';


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
                                        SELECT DISTINCT
                                            CONCAT("Certificado equipo - ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
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
                                            AND equipos_documentos.RUTA_DOCUMENTO <> ""
                                    )
                                    UNION ALL
                                    (
                                        SELECT DISTINCT
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
                                                AND reporterevisiones.agente_id = 8 -- Aire
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
    public function reporteaireworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Ventilación y calidad del aire';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            $zip_nombre = 'Informe de ' . $agente_nombre . ' - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';


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

// // Hola ndo
