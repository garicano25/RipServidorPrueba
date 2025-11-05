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
use App\modelos\reportes\reporteserviciopersonalcatalogoModel;
use App\modelos\reportes\reporteserviciopersonalModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteserviciopersonalcondicioninseguraModel;
use App\modelos\reportes\reporteserviciopersonalcondicioninseguracategoriasModel;
use App\modelos\reportes\reporteserviciopersonalevaluacionpydModel;
use App\modelos\reportes\reporteserviciopersonalevaluacioncatalogoModel;
use App\modelos\reportes\reporteserviciopersonalevaluacionModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;

use App\modelos\proyecto\proyectoproveedoresModel;

use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;
class reporteserviciopersonalwordController extends Controller
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
        }

        $texto = str_replace('CLIENTE_NOMBRE', $recsensorial->cliente->cliente_NombreComercial, $texto);
        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. '.$recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], $texto);
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


                        // $salto_pagina = '<w:pPr>
                        //                     <w:br w:type="page" />
                        //                 </w:pPr>';


                        // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)

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


    public function proporcionalidad($tipologia, $numpersonas)
    {
        $magnitud = 0;

        switch ($tipologia)
        {
            case 1:
                    if($numpersonas > 100)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud =  explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        // dd(round(($numpersonas / 100), 2), round(($numpersonas / 100), 0), $magnitud);

                        $resultado = array('E' => (2*$magnitud[0]), 'L' => (2*$magnitud[0]), 'R' => (2*$magnitud[0]), 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 75)
                    {
                        $resultado = array('E' => 5, 'L' => 4, 'R' => 4, 'M' => 4);
                    }
                    else if ($numpersonas > 50)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 3, 'M' => 3);
                    }
                    else if ($numpersonas > 25)
                    {
                        $resultado = array('E' => 3, 'L' => 3, 'R' => 2, 'M' => 3);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 2, 'R' => 2, 'M' => 2);
                    }
                break;
            case 2:
                    if($numpersonas > 200)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud = explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        $resultado = array('E' => (2*$magnitud[0]), 'L' => (1*$magnitud[0]), 'R' => 0, 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 100)
                    {
                        $resultado = array('E' => 3, 'L' => 2, 'R' => 0, 'M' => 3);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 2, 'R' => 0, 'M' => 2);
                    }
                break;
            default:
                    if($numpersonas > 100)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud = explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        // dd(round(($numpersonas / 100), 2), round(($numpersonas / 100), 0), $magnitud);

                        $resultado = array('E' => (3*$magnitud[0]), 'L' => (2*$magnitud[0]), 'R' => (2*$magnitud[0]), 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 75)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 2, 'M' => 2);
                    }
                    else if ($numpersonas > 50)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 2, 'M' => 2);
                    }
                    else if ($numpersonas > 25)
                    {
                        $resultado = array('E' => 3, 'L' => 2, 'R' => 2, 'M' => 2);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 1, 'R' => 1, 'M' => 2);
                    }
                break;
        }

        return $resultado;
    }


    public function dimencionalidad($metroscuadrados)
    {
        $magnitud = 0;
        $R_E = 0;
        $R_L = 0;
        $R_R = 0;
        $R_M = 0;

        if ($metroscuadrados >= 1000)
        {
            $magnitud = explode('.', round(($metroscuadrados / 1000), 2));

                        // Mujeres + Hombres
            $R_E = ((2*$magnitud[0]) + (1*$magnitud[0]));
            $R_L = ((1*$magnitud[0]) + (1*$magnitud[0]));
            $R_R = 0;
            $R_M = (0 + (2*$magnitud[0]));
        }
        else if ($metroscuadrados >= 900)
        {
            // Mujeres + Hombres
            $R_E = (3 + 2);
            $R_L = (3 + 2);
            $R_R = 0;
            $R_M = (0 + 2);
        }
        else if ($metroscuadrados >= 800)
        {
            // Mujeres + Hombres
            $R_E = (2 + 2);
            $R_L = (2 + 2);
            $R_R = 0;
            $R_M = (0 + 2);
        }
        // else if ($metroscuadrados >= 700)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (2 + 1);
        //     $R_L = (2 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }
        else if ($metroscuadrados >= 600)
        {
            // Mujeres + Hombres
            $R_E = (2 + 1);
            $R_L = (2 + 1);
            $R_R = 0;
            $R_M = (0 + 1);
        }
        else //if ($metroscuadrados >= 500)
        {
            // Mujeres + Hombres
            $R_E = (1 + 1);
            $R_L = (1 + 1);
            $R_R = 0;
            $R_M = (0 + 1);
        }
        // else if ($metroscuadrados >= 400)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (1 + 1);
        //     $R_L = (1 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }
        // else if ($metroscuadrados >= 300)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (1 + 1);
        //     $R_L = (1 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }


        $resultado = array('E' => $R_E, 'L' => $R_L, 'R' => $R_R, 'M' => $R_M);

        return $resultado;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalword(Request $request)
    {
        try
        {
            // dd($request->all());


            $proyecto_id = $request->proyecto_id;
            $reporteregistro_id = $request->reporteregistro_id;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL,"es_MX");


            // $agente_id = 16;
            // $agente_nombre = "Infraestructura para Servicios al Personal";
            // $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            // $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            // $cliente = clienteModel::findOrFail($recsensorial->cliente_id);



            ################ DATOS GENERALES ######################
            $agente_id = 16;
            $agente_nombre = "Infraestructura para Servicios al Personal";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
            $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reporteserviciopersonalModel::where('proyecto_id', $proyecto_id)->get();

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




            if ($reporteregistro_id > 0)
            {
                $reporte  = reporteserviciopersonalModel::findOrFail($reporteregistro_id);
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
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_serviciopersonal.docx'));//Ruta carpeta storage
            }
            else
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_serviciopersonalcliente.docx'));//Ruta carpeta storage
            }


            // PORTADA
            //================================================================================


          ################ PORTADA EXTERNA ####################
          $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
          ->where('clientepartidas_tipo', 2) // Informe de resultados
          ->where('catprueba_id', 16) // Servicio al personal
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


            $cancelado_texto = '';
            if ($revision->reporterevisiones_cancelado == 1)
            {
                $cancelado_texto = '<w:br/>INFORME REVISIÓN '.$revision->reporterevisiones_revision.' CANCELADO';
            }


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
            {
                if ($reporte->reporteserviciopersonal_catsubdireccion_activo == 1)
                {
                    $plantillaword->setValue('SUBDIRECCION', $proyecto->catsubdireccion->catsubdireccion_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('SUBDIRECCION', '');
                }


                if ($reporte->reporteserviciopersonal_catgerencia_activo == 1)
                {
                    $plantillaword->setValue('GERENCIA', $proyecto->catgerencia->catgerencia_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('GERENCIA', '');
                }


                if ($reporte->reporteserviciopersonal_catactivo_activo == 1)
                {
                    $plantillaword->setValue('ACTIVO', $proyecto->catactivo->catactivo_nombre.'<w:br/>');
                    $plantillaword->setValue('ACTIVO2', $proyecto->catactivo->catactivo_nombre);
                }
                else
                {
                    $plantillaword->setValue('ACTIVO', '');
                }


                if ($reporte->reporteserviciopersonal_catregion_activo == 1)
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



            if ($reporte->reporteserviciopersonal_instalacion)
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $reporte->reporteserviciopersonal_instalacion.$cancelado_texto);
            }
            else
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion.$cancelado_texto);
            }



            //-----------------------------------------
            // TITULO DEL INFORME (PARTIDA)

            // $evaluacion = reporteserviciopersonalevaluacionModel::where('proyecto_id', $proyecto_id)->get();

            // $tipoinstacion = '';
            // switch (true)
            // {
            //     case (count($evaluacion) > 50 && ($recsensorial->cliente_id+0) != 2): // cliente_id [2 = senegas]
            //         $tipoinstacion = 'instalación extra grande';
            //         break;
            //     case (count($evaluacion) > 31):
            //         $tipoinstacion = 'instalación grande';
            //         break;
            //     case (count($evaluacion) > 11):
            //         $tipoinstacion = 'instalación mediana';
            //         break;
            //     case (count($evaluacion) > 5):
            //         $tipoinstacion = 'instalación chica';
            //         break;
            //     default:
            //         $tipoinstacion = 'instalación extra chica';
            //         break;
            // }

            $puntosprogramados = proyectoproveedoresModel::where('proyecto_id', $proyecto_id)->where('catprueba_id', $agente_id)->limit(1)->get();

            $tipoinstacion = '';
            switch (true)
            {
                case (($puntosprogramados[0]->proyectoproveedores_puntos+0) > 50 && ($recsensorial->cliente_id+0) != 2): // cliente_id [2 = senegas]
                    $tipoinstacion = 'instalación extra grande';
                    break;
                case (($puntosprogramados[0]->proyectoproveedores_puntos+0) > 31):
                    $tipoinstacion = 'instalación grande';
                    break;
                case (($puntosprogramados[0]->proyectoproveedores_puntos+0) > 11):
                    $tipoinstacion = 'instalación mediana';
                    break;
                case (($puntosprogramados[0]->proyectoproveedores_puntos+0) > 5):
                    $tipoinstacion = 'instalación chica';
                    break;
                default:
                    $tipoinstacion = 'instalación extra chica';
                    break;
            }


            $partidainforme = DB::select('SELECT
                                                 cc.CLIENTE_ID, 
                                                clientepartidas.clientepartidas_tipo, 
                                                clientepartidas.catprueba_id, 
                                                clientepartidas.clientepartidas_nombre, 
                                                clientepartidas.clientepartidas_descripcion
                                            FROM
                                              contratos_partidas clientepartidas
                                                LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = clientepartidas.CONTRATO_ID

                                            WHERE
                                                cc.CLIENTE_ID = '.$recsensorial->cliente_id.'
                                                AND clientepartidas.clientepartidas_tipo = 2 -- 1 = reconocimiento, 2 = informes
                                                AND clientepartidas.catprueba_id = '.$agente_id.'
                                                AND clientepartidas.clientepartidas_descripcion LIKE "%'.$tipoinstacion.'%"
                                           
                                            LIMIT 1');


            if (count($partidainforme) == 0)
            {
                $partidainforme = DB::select('SELECT
                                                 cc.CLIENTE_ID, 
                                                clientepartidas.clientepartidas_tipo, 
                                                clientepartidas.catprueba_id, 
                                                clientepartidas.clientepartidas_nombre, 
                                                clientepartidas.clientepartidas_descripcion
                                            FROM
                                               contratos_partidas  clientepartidas
                                                LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = clientepartidas.CONTRATO_ID

                                            WHERE
                                                cc.CLIENTE_ID = '.$recsensorial->cliente_id.'
                                                AND clientepartidas.clientepartidas_tipo = 2 -- 1 = reconocimiento, 2 = informes
                                                AND clientepartidas.catprueba_id = '.$agente_id.'
                                                -- AND clientepartidas.clientepartidas_descripcion LIKE "%'.$tipoinstacion.'%"
                                            
                                            LIMIT 1');
            }


            if (count($partidainforme) > 0)
            {
                $plantillaword->setValue('TITULO_INFORME', $partidainforme[0]->clientepartidas_descripcion);
                $plantillaword->setValue('PARTIDA', $partidainforme[0]->clientepartidas_descripcion);
            }
            else
            {
                $plantillaword->setValue('TITULO_INFORME', '<w:rPr><w:color w:val="ff0000"/><w:t>FALTA AGREGAR TÍTULO (PARTIDA) DEL INFORME DE INFRAESTRUCTURA DE SERVICIO AL PERSONAL EN EL MÓDULO CLIENTES, CONSULTE CON EL ADMINISTRADOR</w:t></w:rPr>');
                $plantillaword->setValue('PARTIDA', '<w:rPr><w:color w:val="ff0000"/><w:t>FALTA AGREGAR TÍTULO (PARTIDA) DEL INFORME DE INFRAESTRUCTURA DE SERVICIO AL PERSONAL EN EL MÓDULO CLIENTES, CONSULTE CON EL ADMINISTRADOR</w:t></w:rPr>');
            }

            //-----------------------------------------


            $plantillaword->setValue('CONTRATO', $cliente->cliente_numerocontrato);
            $plantillaword->setValue('PORTADA_FECHA', $reporte->reporteserviciopersonal_fecha);
            $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente->cliente_descripcioncontrato);
            $plantillaword->setValue('EMPRESA_RESPONSABLE', $cliente->cliente_plantillaempresaresponsable);
            $plantillaword->setValue('PIE_PAGINA', str_replace("\r\n", "<w:br/>", str_replace("\n\n", "<w:br/>", $cliente->cliente_plantillapiepagina)));


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

            
            $plantillaword->setValue('INTRODUCCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_introduccion));


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


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_objetivoespecifico));


            // 4.- RECONOCIMIENTO INICIAL DE LOS FACTORES
            //================================================================================


            $plantillaword->setValue('RECONOCIMIENTO_FACTORES', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_metodologia_4));


            // 5.- METODOLOGIA
            //================================================================================


            if (($reporte->reporteserviciopersonal_alcanceinforme+0) == 1)
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
                

                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Evaluación de la proporcionalidad y dimensionalidad').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Los servicios sanitarios dentro de las instalaciones de trabajo hoy en día son de vital importancia para las necesidades de cada trabajador, lo cual son de carácter obligatorio. Pero es importante mencionar que dentro de la industria hay riesgos biológicos que están presentes, por lo cual es importante conocer y establecer la organización y cuantificación de los servicios sanitarios presentes dentro de una instalación.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para la identificación de servicios se tomó en cuenta los Reglamentos de Construcción del Municipio del Centro, Estado de Tabasco, así como el Reglamento de Construcciones para el estado de Veracruz-Llave, establecen la cantidad y tipo de servicios con los que debe contar un centro de trabajo, tales como: escusados, lavabos, regaderas y en caso de ser sanitarios para hombres, los mingitorios, tomando en consideración la cantidad de trabajadores y los metros cuadrados (m²) de construcción.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('En lo que se refiere a estos reglamentos, se aclara que no existe una normatividad aplicable a nivel nacional que proporcione bases para la evaluación de la proporcionalidad y dimensionalidad de servicios para el personal, por lo cual se consideraron los reglamentos estatales de construcción, debido a que estos son los únicos que establecen datos específicos en relación al número de baños, mingitorios, lavamanos y regaderas.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:rPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="false"/>
                                    <w:t xml:space="preserve">'.htmlspecialchars('Con la finalidad de una mayor identificación, este método consiste en llevar a cabo los siguientes pasos los cuales ayudaron a identificar el cumplimiento de este.').'</w:t>
                                </w:rPr>';

                
                $plantillaword->setValue('P_1', $txt);


                //---------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#FFFFFF', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.22);
                $col_2 = ($width_table*.04);
                $col_3 = ($width_table*.22);
                $col_4 = ($width_table*.04);
                $col_5 = ($width_table*.22);
                $col_6 = ($width_table*.04);
                $col_7 = ($width_table*.22);

                $table->addRow(600); //fila
                $table->addCell($col_1, $celda)->addTextRun($centrado)->addText('Paso 1', $textonegrita);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('', $textonegrita);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('Paso 2', $textonegrita);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('', $textonegrita);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('Paso 3', $textonegrita);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('', $textonegrita);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText('Paso 4', $textonegrita);

                $table->addRow(200); //fila
                $table->addCell($col_1, array('bgColor' => '#0C3F64', 'valign' => 'center', 'borderSize' => 12, 'borderColor' => '#000000', 'cellMargin' => 100))->addTextRun($centrado)->addText('Verificar el tipo de instalación ', $encabezado_texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('', $encabezado_texto);
                $table->addCell($col_3, array('bgColor' => '#00B050', 'valign' => 'center', 'borderSize' => 12, 'borderColor' => '#000000', 'cellMargin' => 100))->addTextRun($centrado)->addText('Identificación actividades que se realizan', $encabezado_texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('', $encabezado_texto);
                $table->addCell($col_5, array('bgColor' => '#0BACDB', 'valign' => 'center', 'borderSize' => 12, 'borderColor' => '#000000', 'cellMargin' => 100))->addTextRun($centrado)->addText('Conteo de trabajadores', $encabezado_texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('', $encabezado_texto);
                $table->addCell($col_7, array('bgColor' => '#94B796', 'valign' => 'center', 'borderSize' => 12, 'borderColor' => '#000000', 'cellMargin' => 100))->addTextRun($centrado)->addText('Corroborar la superficie de la instalación', $encabezado_texto);

                $plantillaword->setComplexBlock('T_1', $table);


                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Paso 1. Verificar el tipo de instalación').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Durante la verificación de la instalación se corroboró a qué tipo de industria pertenece, estas pueden ser de tipo Industriales, almacenes, bodegas o en su caso oficinas; con el fin de tener en cuenta un amplio panorama de la instalación, así como su tipología en cuanto a la infraestructura.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Paso 2. Identificación actividades que se realizan').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Se identificó el tipo de actividad que realizan dentro de la instalación, registrando las actividades del personal correspondiente en cada una de las áreas de trabajo, además por inspección visual registraron los servicios con los que cuenta, tales como: aire acondicionado, comedor, sanitarios, etc.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Paso 3. Conteo de los trabajadores').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('El conteo de los trabajadores es de vital importancia, este es un factor que facilita la identificación de los servicios con que debe contar una instalación conforme al número total de los trabajadores existentes en las áreas.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Paso 4. Corroborar la superficie de la instalación').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Se corroboró la superficie de la instalación cuantificando el número de servicios sanitarios con el que se cuenta, comparándolo con lo dictaminado en los reglamentos de referencia, para ello se tomó la superficie en metros cuadrados (m²) y la información de apoyo recabada con anterioridad del número total de los trabajadores existentes en las áreas que fueron seleccionadas para la elaboración del estudio.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Cuantificación de trabajadores').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para la cuantificación y estimación de estos servicios se tomó en cuenta el “Reglamento de Construcción del Municipio de Centro, Estado de Tabasco “que aplica para toda obra, ya sea de instalación pública o privada que realice una construcción dentro del territorio, el cual se basa en la cuantificación de servicios sanitarios de acuerdo al número de trabajadores y las actividades que realizan.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para la identificación de servicios sanitarios en industrias como lo son bodegas, almacenes o en instalaciones donde las actividades de los trabajadores generen desaseo se aplicó la siguiente tabla en la cual hace referencia a la cantidad de trabajadores de la instalación.').'</w:t>
                                    </w:rPr>
                                </w:p>';


                $plantillaword->setValue('P_2', $txt);


                //---------------------------------------------------------------------------


                $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.166);
                $col_2 = ($width_table*.166);
                $col_3 = ($width_table*.166);
                $col_4 = ($width_table*.166);
                $col_5 = ($width_table*.166);
                $col_6 = ($width_table*.166);

                $table->addRow(400, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Magnitud', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Escusados', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Lavabos', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Regaderas', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Mingitorios', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText('Industrias, almacenes y bodegas donde se manipule materiales y sustancias que ocasionen manifiesto desaseo', $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Hasta 25 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 26 a 50 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('3', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 51 a 75 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('4', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('3', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 76 a 100 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('5', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('4', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('4', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('4', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Cada 100 adicionales o fracción', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);


                $plantillaword->setComplexBlock('T_2', $table);


                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Tomando en cuenta que el límite es de 100 trabajadores al sobrepasar esta cantidad se le sumaran dos servicios más a partir de la cantidad establecida anteriormente.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Cuando el centro de trabajo sean edificios u oficinas, se debe considerar que no aplica la misma cuantificación que el caso anterior, debido a que las actividades que se realizan no se encuentran dentro del listado de referencia.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('A continuación, se muestra una tabla de los servicios con los que se debe contar para estos centros de trabajo, así como la cantidad mínima que deben cubrir.').'</w:t>
                                    </w:rPr>
                                </w:p>';


                $plantillaword->setValue('P_3', $txt);


                //---------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.166);
                $col_2 = ($width_table*.166);
                $col_3 = ($width_table*.166);
                $col_4 = ($width_table*.166);
                $col_5 = ($width_table*.166);
                $col_6 = ($width_table*.166);

                $table->addRow(400, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Magnitud', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Escusados', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Lavabos', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Regaderas', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Mingitorios', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText('Oficinas', $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Hasta 100 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 101 a 200', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('3', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Cada 100 adicionales o 2 fracción', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);


                $plantillaword->setComplexBlock('T_3', $table);


                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para otro tipo de instalaciones con actividades o infraestructura más específica se debe tener en cuenta los siguientes valores con lo cual se podrá determinar la cuantificación de los servicios sanitarios mínimos que esta debe cumplir, donde de igual forma es importante corroborar las actividades que estos desempeñen.').'</w:t>
                                    </w:rPr>
                                </w:p>';


                $plantillaword->setValue('P_4', $txt);


                //---------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.166);
                $col_2 = ($width_table*.166);
                $col_3 = ($width_table*.166);
                $col_4 = ($width_table*.166);
                $col_5 = ($width_table*.166);
                $col_6 = ($width_table*.166);

                $table->addRow(400, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Magnitud', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Escusados', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Lavabos', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Regaderas', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Mingitorios', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText('Otras industrias, almacenes', $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Hasta 25 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 26 a 50 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('3', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 51 a 75 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('4', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('De 76 a 100 personas', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('4', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Cada 100 adicionales o fracción', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('3', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('2', $texto);


                $plantillaword->setComplexBlock('T_4', $table);


                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Cuantificación de servicios por superficie').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para la cuantificación y estimación de estos servicios se tomó en cuenta el “Reglamento de Construcciones del estado de Veracruz-Llave” que aplica para toda obra ya sea de instalación pública o privada que realice una construcción dentro del territorio, un método el cual se basó en la cuantificación de servicios sanitarios en relación a la superficie en m² de la instalación.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Este método toma en cuenta la superficie en metros cuadrados donde 300 m² es lo mínimo y como máximo 1000 m² (Artículo 141. Servicios sanitarios, Capítulo 10. Edificios para comercios y oficinas).').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Se deben corroborar las dimensiones de la instalación donde el personal lleva a cabo sus actividades, para este método se cuantifica el número de servicios por género, teniendo en cuenta un número de servicios para hombres y otro para mujeres, en el caso de las mujeres la cuantificación será contabilizada como prioridad de acuerdo con lo establecido por cada 1000 m².').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('A continuación, se muestra la tabla con los valores para la cuantificación de los servicios sanitarios conforme a los m² dentro de la instalación.').'</w:t>
                                    </w:rPr>
                                </w:p>';


                $plantillaword->setValue('P_5', $txt);


                //---------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.142);
                $col_2 = ($width_table*.142);
                $col_3 = ($width_table*.142);
                $col_4 = ($width_table*.142);
                $col_5 = ($width_table*.142);
                $col_6 = ($width_table*.142);
                $col_7 = ($width_table*.142);

                $table->addRow(400, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Genero', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Por m²', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Escusados', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Lavabos', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Regaderas', $encabezado_texto);
                $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Mingitorios', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText('Comercios y oficinas', $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Mujeres', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('Por cada 300 m²', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText('0', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Mujeres', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('Por cada 1000 m²', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('2', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText('0', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Hombres', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('Por cada 400 m²', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText('1', $texto);

                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Hombres', $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText('Por cada 1000 m²', $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText('1', $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText('0', $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText('2', $texto);


                $plantillaword->setComplexBlock('T_5', $table);


                //---------------------------------------------------------------------------


                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('NOTA 1: ').'</w:t>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('El uso de estos reglamentos son utilizados únicamente de referencia para las instalaciones que se encuentren ubicadas fuera de los límites de los estados de Veracruz y/o Tabasco.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $txt .= '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('NOTA 2: ').'</w:t>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Para instalaciones que se encuentren ubicadas en los estados de Veracruz o Tabasco, se debe considerar el reglamento aplicable según corresponda.').'</w:t>
                                    </w:rPr>
                                </w:p>';


                $plantillaword->setValue('P_6', $txt);
            }
            else
            {
                $txt = '<w:br/><w:p>
                                    <w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('No aplica.').'</w:t>
                                    </w:rPr>
                                </w:p>';

                $plantillaword->setValue('P_1', $txt);
                $plantillaword->setValue('T_1', '');
                $plantillaword->setValue('P_2', '');
                $plantillaword->setValue('T_2', '');
                $plantillaword->setValue('P_3', '');
                $plantillaword->setValue('T_3', '');
                $plantillaword->setValue('P_4', '');
                $plantillaword->setValue('T_4', '');
                $plantillaword->setValue('P_5', '');
                $plantillaword->setValue('T_5', '');
                $plantillaword->setValue('P_6', '');
            }


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_ubicacioninstalacion));


            // Imagen FOTO
            if ($reporte->reporteserviciopersonal_ubicacionfoto)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteserviciopersonal_ubicacionfoto)))
                {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/'.$reporte->reporteserviciopersonal_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
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


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $reporte->reportevibracion_procesoinstalacion))));


            // SECCION 8.1 PROPORCIONALIDAD Y DIMENCIONALIDAD
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


            if (($reporte->reporteserviciopersonal_alcanceinforme+0) == 1)
            {


                // $txt = '<w:p>
                //             <w:pPr>
                //                 <w:numFmt w:val="upperLetter"/>
                //                 <w:pStyle w:val="Heading 2"/>
                //                 <w:numPr>
                //                     <w:ilvl w:val="1"/>
                //                     <w:numId w:val="13"/>
                //                 </w:numPr>
                //             </w:pPr>
                //             <w:rPr>
                //                 <w:jc w:val="end"/>
                //                 <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                //                 <w:b w:val="true"/>
                //                 <w:t xml:space="preserve">'.htmlspecialchars('Proporcionalidad y dimensionalidad 2').'</w:t>
                //             </w:rPr>
                //         </w:p>';


                // $txt = '<w:p>
                //             <w:pStyle w:val="Heading 2"/>
                //             <w:name w:val="Título 2"/>
                //             <w:next w:val="Normal"/>
                //             <w:pPr>
                //                 <w:keepNext/>
                //                 <w:keepLines/>
                //                 <w:numPr>
                //                     <w:ilvl w:val="1"/>
                //                     <w:numId w:val="13"/>
                //                 </w:numPr>
                //                 <w:spacing w:before="0" w:after="0"/>
                //                 <w:outlineLvl w:val="0"/>
                //             </w:pPr>
                //             <w:rPr>
                //                 <w:name w:val="Heading2"/>
                //                 <w:rFonts w:ascii="Poppins Black" w:hAnsi="Poppins Black"/>
                //                 <w:b/>
                //                 <w:color w:val="365F91"/>
                //                 <w:sz w:val="28"/>
                //                 <w:t xml:space="preserve">Proporcionalidad y dimensionalidad 2</w:t>
                //             </w:rPr>
                //         </w:p>';


                // $txt = '<w:p>
                //             <w:pPr>
                //                 <w:pStyle w:val="Heading1"/>
                //                 <w:name w:val="Título 1"/>
                //                 <w:numPr>
                //                     <w:ilvl w:val="1"/>
                //                     <w:numId w:val="13"/>
                //                 </w:numPr>
                //             </w:pPr>
                //             <w:r>
                //                 <w:t>This is the first . . .</w:t>
                //             </w:r>
                //         </w:p>';


                // $plantillaword->setValue('8_1_TITULO', $txt);
                $plantillaword->setValue('8_1_TITULO', 'Proporcionalidad y dimensionalidad');


                $txt = '<w:br/><w:rPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="false"/>
                                    <w:t xml:space="preserve">'.htmlspecialchars('La proporcionalidad y dimensionalidad de los servicios para el personal, son un punto importante para el correcto desarrollo de las actividades en cada uno de los puestos de trabajo, esto, debido a que, al estar el trabajador cubierto por los servicios básicos, realiza sus actividades de manera correcta y estable.').'</w:t>
                                </w:rPr><w:br/>';

                $txt .= '<w:br/><w:rPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="false"/>
                                    <w:t xml:space="preserve">'.htmlspecialchars('A continuación, se presenta la tabla comparativa en cumplimiento respecto a la proporcionalidad y dimensionalidad de los servicios, en relación al Reglamento de Construcciones para el estado de Veracruz-Llave y al Reglamento de Construcciones del Municipio del Centro, Estado de Tabasco vigente:').'</w:t>
                                </w:rPr>';

                
                $plantillaword->setValue('PYD_P1', $txt);


                //---------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.142);
                $col_2 = ($width_table*.142);
                $col_3 = ($width_table*.142);
                $col_4 = ($width_table*.142);
                $col_5 = ($width_table*.142);
                $col_6 = ($width_table*.142);
                $col_7 = ($width_table*.142);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Tabla comparativa de la proporcionalidad', $textonegrita);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Proporcionalidad de acuerdo a magnitud de personas', $textonegrita);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área / Instalación', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Número de personas', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Servicio sanitario', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Numero con el que cuentan', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Numero solicitado por reglamento', $encabezado_texto);
                $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento', $encabezado_texto);


                $pyd = reporteserviciopersonalevaluacionpydModel::where('proyecto_id', $request->proyecto_id)->get();


                if (count($pyd) > 0)
                {
                    $pyd = $pyd[0];
                    $tipologia = '';


                    if ($pyd->reporteserviciopersonalevaluacionpyd_tipologia == 1)
                    {
                        $tipologia = 'Industrias, almacenes y bodegas donde se manipule materiales y sustancias que ocasionen manifiesto desaseo';
                    }
                    else if ($pyd->reporteserviciopersonalevaluacionpyd_tipologia == 2)
                    {
                        $tipologia = 'Oficinas';
                    }
                    else
                    {
                        $tipologia = 'Otras industrias, almacenes';
                    }


                    $proporcionalidad = $this->proporcionalidad(($pyd->reporteserviciopersonalevaluacionpyd_tipologia + 0), ($pyd->reporteserviciopersonalevaluacionpyd_personas + 0));


                    $E_resultado = 'Si cumple'; $E_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $E_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    $L_resultado = 'Si cumple'; $L_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $L_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    $M_resultado = 'Si cumple'; $M_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $M_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    $R_resultado = 'Si cumple'; $R_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $R_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);


                    if (($pyd->reporteserviciopersonalevaluacionpyd_escusados+0) < ($proporcionalidad['E']+0))
                    {
                        $E_resultado = 'No cumple';
                        $E_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                        $E_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    }

                    if (($pyd->reporteserviciopersonalevaluacionpyd_lavabos+0) < ($proporcionalidad['L']+0))
                    {
                        $L_resultado = 'No cumple';
                        $L_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                        $L_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    }

                    if (($pyd->reporteserviciopersonalevaluacionpyd_mingitorios+0) < ($proporcionalidad['M']+0))
                    {
                        $M_resultado = 'No cumple';
                        $M_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                        $M_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    }

                    if (($pyd->reporteserviciopersonalevaluacionpyd_Regaderas+0) < ($proporcionalidad['R']+0))
                    {
                        $R_resultado = 'No cumple';
                        $R_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                        $R_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    }


                    $table->addRow(800); //fila
                    $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_areainstalacion), $texto);
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_personas), $texto);
                    $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($tipologia), $texto);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Escusados'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_escusados), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($proporcionalidad['E']), $texto);
                    $table->addCell($col_7, $E_celda)->addTextRun($centrado)->addText(htmlspecialchars($E_resultado), $E_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Lavabos'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_lavabos), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($proporcionalidad['L']), $texto);
                    $table->addCell($col_7, $L_celda)->addTextRun($centrado)->addText(htmlspecialchars($L_resultado), $L_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Mingitorios'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_mingitorios), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($proporcionalidad['M']), $texto);
                    $table->addCell($col_7, $M_celda)->addTextRun($centrado)->addText(htmlspecialchars($M_resultado), $M_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Regaderas'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_Regaderas), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($proporcionalidad['R']), $texto);
                    $table->addCell($col_7, $R_celda)->addTextRun($centrado)->addText(htmlspecialchars($R_resultado), $R_texto);


                    $txt = '<w:br/><w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:sz w:val="20"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Referencia: ').'</w:t>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Cumplimiento de proporcionalidad y dimensional de acuerdo con lo establecido en el Reglamento de Construcción para el estado de Veracruz-Llave y Reglamento de Construcción del Municipio del Centro- Estado de Tabasco.').'</w:t>
                                    </w:rPr>';

                    if ($pyd->reporteserviciopersonalevaluacionpyd_notap)
                    {
                        $txt .= '<w:p></w:p><w:p></w:p><w:rPr>
                                                            <w:jc w:val="both"/>
                                                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                            <w:sz w:val="20"/>
                                                            <w:b w:val="true"/>
                                                            <w:t xml:space="preserve">'.htmlspecialchars('Nota: ').'</w:t>
                                                            <w:b w:val="false"/>
                                                            <w:t xml:space="preserve">'.htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_notap).'</w:t>
                                                        </w:rPr>';
                    }


                    $table->addRow(200);
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($justificado)->addText($txt, $texto);
                }
                else
                {
                    $table->addRow(200);
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#000000'))->addTextRun($centrado)->addText('Sin evaluar', $texto);
                }


                $plantillaword->setComplexBlock('PYD_T1', $table);


                // ----------------------------------------------------------------------------


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.142);
                $col_2 = ($width_table*.142);
                $col_3 = ($width_table*.142);
                $col_4 = ($width_table*.142);
                $col_5 = ($width_table*.142);
                $col_6 = ($width_table*.142);
                $col_7 = ($width_table*.142);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Tabla comparativa de dimensionalidad', $textonegrita);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Dimensionalidad conforme los m² de la instalación', $textonegrita);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área / Instalación', $encabezado_texto);
                $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('m² de la instalación', $encabezado_texto);
                $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Tipología', $encabezado_texto);
                $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Servicio sanitario', $encabezado_texto);
                $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Numero con el que cuentan', $encabezado_texto);
                $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Numero solicitado por reglamento', $encabezado_texto);
                $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento', $encabezado_texto);


                $pyd = reporteserviciopersonalevaluacionpydModel::where('proyecto_id', $request->proyecto_id)->get();


                if (count($pyd) > 0)
                {
                    $pyd = $pyd[0];


                    if (($pyd->reporteserviciopersonalevaluacionpyd_m2 + 0) > 0)
                    {
                        $dimencionalidad = $this->dimencionalidad(($pyd->reporteserviciopersonalevaluacionpyd_m2 + 0));


                        $m2 = $pyd->reporteserviciopersonalevaluacionpyd_m2;
                        $E_resultado = 'Si cumple'; $E_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $E_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $L_resultado = 'Si cumple'; $L_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $L_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $M_resultado = 'Si cumple'; $M_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $M_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $R_resultado = 'Si cumple'; $R_celda = array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100); $R_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);


                        if (($pyd->reporteserviciopersonalevaluacionpyd_escusados+0) < ($dimencionalidad['E']+0))
                        {
                            $E_resultado = 'No cumple';
                            $E_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                            $E_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        }

                        if (($pyd->reporteserviciopersonalevaluacionpyd_lavabos+0) < ($dimencionalidad['L']+0))
                        {
                            $L_resultado = 'No cumple';
                            $L_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                            $L_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        }

                        if (($pyd->reporteserviciopersonalevaluacionpyd_mingitorios+0) < ($dimencionalidad['M']+0))
                        {
                            $M_resultado = 'No cumple';
                            $M_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                            $M_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        }

                        if (($pyd->reporteserviciopersonalevaluacionpyd_Regaderas+0) < ($dimencionalidad['R']+0))
                        {
                            $R_resultado = 'No cumple';
                            $R_celda = array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                            $R_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        }
                    }
                    else
                    {
                        $dimencionalidad = array('E' => 'N/A', 'L' => 'N/A', 'R' => 'N/A', 'M' => 'N/A');


                        $m2 = 'N/A';
                        $E_resultado = 'No aplica'; $E_celda = array('bgColor' => '#FFFFFF', 'valign' => 'center', 'cellMargin' => 100); $E_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $L_resultado = 'No aplica'; $L_celda = array('bgColor' => '#FFFFFF', 'valign' => 'center', 'cellMargin' => 100); $L_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $M_resultado = 'No aplica'; $M_celda = array('bgColor' => '#FFFFFF', 'valign' => 'center', 'cellMargin' => 100); $M_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                        $R_resultado = 'No aplica'; $R_celda = array('bgColor' => '#FFFFFF', 'valign' => 'center', 'cellMargin' => 100); $R_texto = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                    }


                    $table->addRow(800); //fila
                    $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_areainstalacion), $texto);
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($m2), $texto);
                    $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText(htmlspecialchars($tipologia), $texto);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Escusados'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_escusados), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($dimencionalidad['E']), $texto);
                    $table->addCell($col_7, $E_celda)->addTextRun($centrado)->addText(htmlspecialchars($E_resultado), $E_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Lavabos'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_lavabos), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($dimencionalidad['L']), $texto);
                    $table->addCell($col_7, $L_celda)->addTextRun($centrado)->addText(htmlspecialchars($L_resultado), $L_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Mingitorios'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_mingitorios), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($dimencionalidad['M']), $texto);
                    $table->addCell($col_7, $M_celda)->addTextRun($centrado)->addText(htmlspecialchars($M_resultado), $M_texto);

                    $table->addRow(800); //fila
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                    $table->addCell($col_3, $continua_fila);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars('Regaderas'), $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_Regaderas), $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText(htmlspecialchars($dimencionalidad['R']), $texto);
                    $table->addCell($col_7, $R_celda)->addTextRun($centrado)->addText(htmlspecialchars($R_resultado), $R_texto);

                    $txt = '<w:br/><w:rPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        <w:sz w:val="20"/>
                                        <w:b w:val="true"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Referencia: ').'</w:t>
                                        <w:b w:val="false"/>
                                        <w:t xml:space="preserve">'.htmlspecialchars('Cumplimiento de proporcionalidad y dimensional de acuerdo con lo establecido en el Reglamento de Construcción para el estado de Veracruz-Llave y Reglamento de Construcción del Municipio del Centro- Estado de Tabasco.').'</w:t>
                                    </w:rPr>';

                    if ($pyd->reporteserviciopersonalevaluacionpyd_notad)
                    {
                        $txt .= '<w:p></w:p><w:p></w:p><w:rPr>
                                                            <w:jc w:val="both"/>
                                                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                            <w:sz w:val="20"/>
                                                            <w:b w:val="true"/>
                                                            <w:t xml:space="preserve">'.htmlspecialchars('Nota: ').'</w:t>
                                                            <w:b w:val="false"/>
                                                            <w:t xml:space="preserve">'.htmlspecialchars($pyd->reporteserviciopersonalevaluacionpyd_notad).'</w:t>
                                                        </w:rPr>';
                    }


                    $table->addRow(200);
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($justificado)->addText($txt, $texto);
                }
                else
                {
                    $table->addRow(200);
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#000000'))->addTextRun($centrado)->addText('Sin evaluar', $texto);
                }


                $plantillaword->setComplexBlock('PYD_T2', $table);


                // ----------------------------------------------------------------------------

                
                // CREAR TABLA
                $table = null; $width_table = 5000;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#FFFFFF', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.15);
                $col_2 = ($width_table*.35);
                $col_3 = ($width_table*.15);
                $col_4 = ($width_table*.35);

                $table->addRow(300); //fila
                $table->addCell($col_1, array('bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100))->addTextRun($izquierda)->addText('', $textonegrita);
                $table->addCell($col_2, $celda)->addTextRun($izquierda)->addText('Si cumple', $textonegrita);
                $table->addCell($col_3, array('bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100))->addTextRun($izquierda)->addText('', $textonegrita);
                $table->addCell($col_4, $celda)->addTextRun($izquierda)->addText('No cumple', $textonegrita);


                $plantillaword->setComplexBlock('PYD_T1_COLOR', $table);
                $plantillaword->setComplexBlock('PYD_T2_COLOR', $table);

                $plantillaword->setValue('EVALUACION1_SALTO', '<w:pPr><w:br w:type="page"/></w:pPr>');
            }
            else
            {
                $plantillaword->setValue('8_1_TITULO', '');
                $plantillaword->setValue('PYD_P1', '');
                $plantillaword->setValue('PYD_T1', '');
                $plantillaword->setValue('PYD_T1_COLOR', '');
                $plantillaword->setValue('PYD_T2', '');
                $plantillaword->setValue('PYD_T2_COLOR', '');
                $plantillaword->setValue('EVALUACION1_SALTO', '');
            }


            // SECCION 8.2 CONDICIONES FISICAS DE HIGIENE Y SEGURIDAD
            //================================================================================


            // $evaluacion = reporteserviciopersonalevaluacionModel::where('proyecto_id', $request->proyecto_id)->orderby('reporteserviciopersonalevaluacion_punto', 'ASC')->get();
            $a = reporteserviciopersonalevaluacionModel::where('proyecto_id', $proyecto_id)->where('reporteserviciopersonalevaluacioncatalogo_id', '>', 0)->orderBy('reporteserviciopersonalevaluacion_punto', 'ASC')->get(); //Normas
            $b = reporteserviciopersonalevaluacionModel::where('proyecto_id', $proyecto_id)->where('reporteserviciopersonalevaluacioncatalogo_id', '=', 0)->orderBy('reporteserviciopersonalevaluacion_punto', 'ASC')->get(); //Procedimientos
            $evaluacion = $a->merge($b);


            if (count($evaluacion) > 0)
            {
                if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
                {
                    $fuente = 'Poppins';
                    $font_size = 9;
                }
                else
                {
                    $fuente = 'Montserrat';
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


                // CREAR TABLA
                $table = null; $width_table = 8850;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                // ANCHO COLUMNAS
                $col_1 = ($width_table*.06);
                $col_2 = ($width_table*.20);
                $col_3 = ($width_table*.06);
                $col_4 = ($width_table*.68);


                $procedimientos_metodologia_total = 0;
                $procedimientos_metodologia = '';

                foreach ($evaluacion as $key_eval => $value) 
                {
                    if ($value->reporteserviciopersonalevaluacion_procedimiento)
                    {
                        if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1600)
                        {
                            $txt_sz = 3;
                        }
                        else if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1300)
                        {
                            $txt_sz = 2;
                        }
                        else if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1000)
                        {
                            $txt_sz = 1;
                        }
                        else
                        {
                            $txt_sz = 0;
                        }
                    }
                    else
                    {
                        if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1900)
                        {
                            $txt_sz = 3;
                        }
                        else if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1600)
                        {
                            $txt_sz = 2;
                        }
                        else if ((strlen($value->reporteserviciopersonalevaluacion_descripcion)+0) > 1300)
                        {
                            $txt_sz = 1;
                        }
                        else
                        {
                            $txt_sz = 0;
                        }
                    }


                    $titulo = '<w:p>
                                <w:rPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    <w:b w:val="true"/>
                                    <w:sz w:val="'.($font_size*2).'"/>
                                    <w:t xml:space="preserve">'.htmlspecialchars($value->reporteserviciopersonalevaluacion_titulo).'</w:t>
                                </w:rPr>
                            </w:p>';


                    $parrafos = explode("\n", $value->reporteserviciopersonalevaluacion_descripcion);
                    $descripcion = '';


                    if (count($parrafos) > 0)
                    {
                        foreach($parrafos as $key => $parrafo)
                        {
                            if ($key < (count($parrafos)-1))
                            {
                                $descripcion .= '<w:p>
                                                    <w:rPr>
                                                        <w:jc w:val="both"/>
                                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                        <w:b w:val="false"/>
                                                        <w:sz w:val="'.(($font_size - $txt_sz) * 2).'"/>
                                                        <w:t xml:space="preserve">'.htmlspecialchars($parrafo).'</w:t>
                                                    </w:rPr>
                                                </w:p>';
                            }
                            else
                            {
                                $descripcion .= '<w:rPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                    <w:b w:val="false"/>
                                                    <w:sz w:val="'.(($font_size - $txt_sz) * 2).'"/>
                                                    <w:t xml:space="preserve">'.htmlspecialchars($parrafo).'</w:t>
                                                </w:rPr>';
                            }
                        }
                    }
                    else
                    {
                        $descripcion .= '<w:rPr>
                                            <w:jc w:val="both"/>
                                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            <w:b w:val="false"/>
                                            <w:sz w:val="'.(($font_size - $txt_sz) * 2).'"/>
                                            <w:t xml:space="preserve">'.htmlspecialchars($parrafo).'</w:t>
                                        </w:rPr>';
                    }


                    switch ($value->reporteserviciopersonalevaluacion_cumplimiento)
                    {
                        case 'Si cumple':
                            $resultado_celda = array('gridSpan' => 2, 'bgColor' => '#00FF00', 'valign' => 'center', 'cellMargin' => 100);
                            $resultado_color = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                            break;
                        case 'No cumple':
                            $resultado_celda = array('gridSpan' => 2, 'bgColor' => '#FF0000', 'valign' => 'center', 'cellMargin' => 100);
                            $resultado_color = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                            break;
                        default: // No aplica
                            $resultado_celda = array('gridSpan' => 2, 'bgColor' => '#DDDDDD', 'valign' => 'center', 'cellMargin' => 100);
                            $resultado_color = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                            break;
                    }


                    //---------------------------------------------------


                    if ($value->reporteserviciopersonalevaluacion_procedimiento)
                    {
                        $procedimientos_metodologia_total += 1;


                        $font_size_m = 11;
                        if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
                        {
                            $font_size_m = 12;
                        }

                        $procedimientos_metodologia .= '<w:br/><w:p>
                                                                    <w:rPr>
                                                                        <w:jc w:val="end"/>
                                                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                                        <w:b w:val="false"/>
                                                                        <w:sz w:val="'.($font_size_m*2).'"/>
                                                                        <w:t xml:space="preserve">● '.htmlspecialchars($value->reporteserviciopersonalevaluacion_procedimiento).'</w:t>
                                                                    </w:rPr>
                                                                </w:p>';


                        // -------------------------------------------------------


                        $font_size_p = 11;
                        if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
                        {
                            $font_size_p = 12;
                        }

                        $procedimiento = '<w:p>
                                                <w:rPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                    <w:b w:val="false"/>
                                                    <w:sz w:val="'.($font_size_p*2).'"/>
                                                    <w:t xml:space="preserve">'.htmlspecialchars('De acuerdo con los puntos a evaluar y con base en los lineamientos establecidos por el procedimiento '.$value->reporteserviciopersonalevaluacion_procedimiento).'</w:t>
                                                </w:rPr>
                                            </w:p>';

                        $table->addRow(700);
                        $table->addCell(($col_1+$col_2+$col_3+$col_4), array('gridSpan' => 4, 'valign' => 'center', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($justificado)->addText($procedimiento, $texto);
                    }



                    $table->addRow(200);
                    $table->addCell(($col_1+$col_2+$col_3+$col_4), array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Evaluación de las instalaciones y servicios ', $encabezado_texto);

                    $table->addRow(200);
                    $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('No', $encabezado_texto);
                    $table->addCell(($col_2+$col_3+$col_4), array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText($value->reporteserviciopersonalevaluacion_lugar, $encabezado_texto);

                    $table->addRow(2700);
                    $table->addCell($col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText($value->reporteserviciopersonalevaluacion_punto, $encabezado_texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Evidencia', $textonegrita);
                    $table->addCell(($col_3+$col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($centrado)->addText('${EVAL'.$value->id.'_EVI_1}  ${EVAL'.$value->id.'_EVI_2}', $texto);


                    if (($key_eval+0) > 0)
                    {
                        if ($value->reporteserviciopersonalevaluacion_procedimiento)
                        {
                            $table->addRow(4300);
                        }
                        else
                        {
                            $table->addRow(5500);
                        }
                    }
                    else
                    {
                        if ($value->reporteserviciopersonalevaluacion_procedimiento)
                        {
                            $table->addRow(2600);
                        }
                        else
                        {
                            $table->addRow(3800);
                        }
                    }


                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Punto a verificar', $textonegrita);
                    $table->addCell(($col_3+$col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($justificado)->addText($titulo.'<w:br/>'.$descripcion, $texto);
                    // $table->addCell(($col_3+$col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($justificado)->addText($titulo.'<w:br/>', $texto);

                    $table->addRow(1800);
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Observaciones', $textonegrita);
                    $table->addCell(($col_3+$col_4), array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($justificado)->addText($value->reporteserviciopersonalevaluacion_observacion, $texto);

                    $table->addRow(200);
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('Cumplimiento', $textonegrita);
                    $table->addCell(($col_3+$col_4), $resultado_celda)->addTextRun($izquierda)->addText($value->reporteserviciopersonalevaluacion_cumplimiento, $resultado_color);

                    $table->addRow(100);
                    $table->addCell(($col_1+$col_2+$col_3+$col_4), array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($justificado)->addText('', $texto);

                    $table->addRow(300);
                    $table->addCell($col_1, array('bgColor' => '#00FF00', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($centrado)->addText('', $texto);
                    $table->addCell($col_2, array('valign' => 'center', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('Si cumple', $textonegrita);
                    $table->addCell($col_3, array('bgColor' => '#FF0000', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($centrado)->addText('', $texto);
                    $table->addCell($col_4, array('valign' => 'center', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('No cumple', $textonegrita);

                    if (($key_eval+0) < (count($evaluacion)-1))
                    {
                        $table->addRow(200);
                        $table->addCell(($col_1+$col_2+$col_3+$col_4), array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($justificado)->addText('', $texto);
                    }
                }


                $plantillaword->setComplexBlock('EVALUACION_TABLA', $table);


                // -------------------------------------------------


                if (($procedimientos_metodologia_total+0) > 1)
                {
                    $plantillaword->setValue('PROCEDIMIENTOS_METODOLOGIA', 'Así como también lo aplicable por los procedimientos:<w:br/>'.$procedimientos_metodologia);
                }
                else if (($procedimientos_metodologia_total+0) > 0)
                {
                    $plantillaword->setValue('PROCEDIMIENTOS_METODOLOGIA', 'Así como también lo aplicable por el procedimiento:<w:br/>'.$procedimientos_metodologia);
                }
                else
                {
                    $plantillaword->setValue('PROCEDIMIENTOS_METODOLOGIA', '<w:br/>');
                }
            }
            else
            {
                $plantillaword->setValue('EVALUACION_TABLA', 'SIN EVALUAR.');
            }


            // 8.3 FAUNA NOCIVA
            //================================================================================


            $plantillaword->setValue('FAUNA_NOCIVA', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_metodologia_8_3));


            // 8.4 FOCOS DE ANIDACION DE FAUNA NOCIVA
            //================================================================================


            $plantillaword->setValue('FAUNA_NOCIVA_FOCO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_metodologia_8_4));


            // CONCLUSION
            //================================================================================


            $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_conclusion));


            // RECOMENDACIONES
            //================================================================================


            $plantillaword->setValue('RECOMENDACIONES', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteserviciopersonal_recomendaciones));


            // RESPONSABLES
            //================================================================================


            // RESPONSABLE 1, FOTO DOCUMENTO
            if ($reporte->reporteserviciopersonal_responsable1documento)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteserviciopersonal_responsable1documento)))
                {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/'.$reporte->reporteserviciopersonal_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
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
                

            $plantillaword->setValue('REPONSABLE1', $reporte->reporteserviciopersonal_responsable1."<w:br/>".$reporte->reporteserviciopersonal_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporte->reporteserviciopersonal_responsable2documento)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteserviciopersonal_responsable2documento)))
                {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/'.$reporte->reporteserviciopersonal_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
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
                

            $plantillaword->setValue('REPONSABLE2', $reporte->reporteserviciopersonal_responsable2."<w:br/>".$reporte->reporteserviciopersonal_responsable2cargo);


            // ANEXO 12.2. CONDICIONES INSEGURAS DETECTADAS
            //================================================================================


            // $condicion_insegura = reporteserviciopersonalcondicioninseguraModel::where('proyecto_id', $request->proyecto_id)->orderby('id', 'ASC')->get();


            $condicion_insegura = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.id,
                                                    TABLA.reportearea_id,
                                                    TABLA.reportearea_nombre,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_actividad,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_rutinario,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_descripcion,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_clasificacion,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_efectos,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_fuente,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_medio,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_probabilidad,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_exposicion,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_consecuencia,
                                                    ROUND(resultado, 1) AS resultado,
                                                    (
                                                        CASE
                                                            WHEN resultado >= 400 THEN "#FF0000"
                                                            WHEN resultado >= 200 THEN "#FF6600"
                                                            WHEN resultado >= 70 THEN "#FFFF00"
                                                            WHEN resultado >= 20 THEN "#006600"
                                                            ELSE "#92D050"
                                                        END
                                                    ) AS resultado_color,
                                                    (
                                                        CASE
                                                            WHEN resultado >= 70 && resultado < 200 THEN "#000000"
                                                            ELSE "#FFFFFF"
                                                        END
                                                    ) AS resultado_colortext,
                                                    (
                                                        CASE
                                                            WHEN resultado >= 400 THEN "Paralización de la actividad de forma inmediata"
                                                            WHEN resultado >= 200 THEN "Corrección inmediata"
                                                            WHEN resultado >= 70 THEN "Corrección necesaria urgente"
                                                            WHEN resultado >= 20 THEN "No es urgente, pero debe corregirse"
                                                            ELSE "Puede omitirse la corrección"
                                                        END
                                                    ) AS resultado_texto,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_foto1,
                                                    TABLA.reporteserviciopersonalcondicioninsegura_foto2,
                                                    categorias
                                                FROM
                                                    (
                                                        SELECT
                                                            reporteserviciopersonalcondicioninsegura.proyecto_id,
                                                            reporteserviciopersonalcondicioninsegura.id,
                                                            reporteserviciopersonalcondicioninsegura.reportearea_id,
                                                            reportearea.reportearea_nombre,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_actividad,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_rutinario,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_descripcion,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_clasificacion,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_efectos,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_fuente,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_medio,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_probabilidad,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_exposicion,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_consecuencia,
                                                            (
                                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_probabilidad *
                                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_exposicion *
                                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_consecuencia
                                                            ) AS resultado,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_foto1,
                                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_foto2,
                                                            IFNULL((
                                                                SELECT
                                                                    -- reporteserviciopersonalcondicioninseguracategorias.reporteserviciopersonalcondicioninsegura_id,
                                                                    -- reporteserviciopersonalcondicioninseguracategorias.id,
                                                                    -- reporteserviciopersonalcondicioninseguracategorias.reportecategoria_id,
                                                                    -- reportecategoria.reportecategoria_orden,
                                                                    -- reportecategoria.reportecategoria_nombre
                                                                    CONCAT("● ", REPLACE(GROUP_CONCAT(reportecategoria.reportecategoria_nombre ORDER BY reportecategoria.reportecategoria_orden ASC, reportecategoria.reportecategoria_nombre ASC), ",", "<w:br/>● ")) AS categorias
                                                                FROM
                                                                    reporteserviciopersonalcondicioninseguracategorias
                                                                    LEFT JOIN reportecategoria ON reporteserviciopersonalcondicioninseguracategorias.reportecategoria_id = reportecategoria.id
                                                                WHERE
                                                                    reporteserviciopersonalcondicioninseguracategorias.reporteserviciopersonalcondicioninsegura_id = reporteserviciopersonalcondicioninsegura.id
                                                                ORDER BY
                                                                    reportecategoria.reportecategoria_orden ASC,
                                                                    reportecategoria.reportecategoria_nombre ASC
                                                            ), "N/A") AS categorias
                                                        FROM
                                                            reporteserviciopersonalcondicioninsegura
                                                            LEFT JOIN reportearea ON reporteserviciopersonalcondicioninsegura.reportearea_id = reportearea.id 
                                                        WHERE
                                                            reporteserviciopersonalcondicioninsegura.proyecto_id = '.$proyecto_id.' 
                                                        ORDER BY
                                                            reporteserviciopersonalcondicioninsegura.id ASC
                                                    ) AS TABLA');


            if (count($condicion_insegura) > 0)
            {
                if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
                {
                    $fuente = 'Poppins';
                    $font_size = 9;
                }
                else
                {
                    $fuente = 'Montserrat';
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


                // CREAR TABLA
                $table = null; $width_table = 9950;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


                // ANCHO COLUMNAS
                $col_1 = ($width_table*.30);
                $col_2 = ($width_table*.70);


                $table->addRow(400, array('tblHeader' => true));
                $table->addCell(($col_1+$col_2), array('gridSpan' => 2, 'valign' => 'top', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Valoración de Riesgo de la condición insegura detectada', array('color' => '000000', 'size' => 11, 'bold' => true, 'name' => $fuente));


                foreach ($condicion_insegura as $key => $value) 
                {
                    $table->addRow(200);
                    $table->addCell(($col_1+$col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Condiciones inseguras: peligros y riesgos detectados', $encabezado_texto);

                    $table->addRow(200);
                    $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Lugar de trabajo', $encabezado_texto);
                    $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Fotografía', $encabezado_texto);

                    $table->addRow(3200);
                    $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText('${COND'.$value->id.'_EVI_1}  ${COND'.$value->id.'_EVI_2}', $texto);

                    $table->addRow(200);
                    $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Peligro', $encabezado_texto);
                    $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Riesgo', $encabezado_texto);

                    $table->addRow(1200);
                    $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_descripcion, $texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_efectos, $texto);

                    if (($key+0) < (count($condicion_insegura)-1))
                    {
                        $table->addRow(400);
                        $table->addCell(($col_1+$col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '#FFFFFF', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($centrado)->addText('', $texto);
                    }
                }


                $plantillaword->setComplexBlock('CONDICION_INSEGURA_T1', $table);


                //------------------------------------------------------------


                $txt = '<w:pPr>
                            <w:br w:type="page" />
                        </w:pPr>';


                $plantillaword->setValue('CONDICION_SALTOLINEA', $txt);


                //------------------------------------------------------------


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


                // CREAR TABLA
                $table = null; $width_table = 9950;
                $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 1, 'borderColor' => '#000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


                // ANCHO COLUMNAS
                $col_1 = ($width_table*.08);
                $col_2 = ($width_table*.08);
                $col_3 = ($width_table*.05);
                $col_4 = ($width_table*.08);
                $col_5 = ($width_table*.08);
                $col_6 = ($width_table*.08);
                $col_7 = ($width_table*.08);
                $col_8 = ($width_table*.08);
                $col_9 = ($width_table*.26);
                $col_10 = ($width_table*.01);
                $col_11 = ($width_table*.01);
                $col_12 = ($width_table*.01);
                $col_13 = ($width_table*.08);


                $table->addRow(400, array('tblHeader' => true));
                $table->addCell(($width_table), array('gridSpan' => 13, 'valign' => 'top', 'borderSize' => 1, 'borderColor' => '#FFFFFF'))->addTextRun($centrado)->addText('Valoración de Riesgo de la condición insegura detectada', array('color' => '000000', 'size' => 11, 'bold' => true, 'name' => $fuente));

                $table->addRow(500, array('tblHeader' => true));
                $table->addCell($col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Zona o<w:br/>área de<w:br/>trabajo', $encabezado_texto);
                $table->addCell($col_2, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Actividad<w:br/>general', $encabezado_texto);
                $table->addCell($col_3, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Rutinario<w:br/>(si o no)', $encabezado_texto);
                $table->addCell(($col_4+$col_5), array('gridSpan' => 2, 'bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100))->addTextRun($centrado)->addText('Peligro', $encabezado_texto);
                $table->addCell($col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Efectos<w:br/>posibles', $encabezado_texto);
                $table->addCell(($col_7+$col_8+$col_9), array('gridSpan' => 3, 'bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100))->addTextRun($centrado)->addText('Controles existentes', $encabezado_texto);
                $table->addCell(($col_10+$col_11+$col_12), array('gridSpan' => 3, 'bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100))->addTextRun($centrado)->addText('Evaluación<w:br/>el riesgo', $encabezado_texto);
                $table->addCell($col_13, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Grado de<w:br/>peligrosidad<w:br/><w:br/>GP=PxExC', $encabezado_texto);

                $table->addRow(1300, array('tblHeader' => true));
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $continua_fila);
                $table->addCell($col_3, $continua_fila);
                $table->addCell($col_4, array('bgColor' => '#0BACDB', 'valign' => 'center'))->addTextRun($centrado)->addText('Descripción', $encabezado_texto);
                $table->addCell($col_5, array('bgColor' => '#0BACDB', 'valign' => 'center'))->addTextRun($centrado)->addText('Clasificación', $encabezado_texto);
                $table->addCell($col_6, $continua_fila);
                $table->addCell($col_7, array('bgColor' => '#0BACDB', 'valign' => 'center'))->addTextRun($centrado)->addText('Fuente', $encabezado_texto);
                $table->addCell($col_8, array('bgColor' => '#0BACDB', 'valign' => 'center'))->addTextRun($centrado)->addText('Medio', $encabezado_texto);
                $table->addCell($col_9, array('bgColor' => '#0BACDB', 'valign' => 'center'))->addTextRun($centrado)->addText('Individuo', $encabezado_texto);
                $table->addCell($col_10, array('bgColor' => '#0BACDB', 'valign' => 'center', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado)->addText('Probabilidad', $encabezado_texto);
                $table->addCell($col_11, array('bgColor' => '#0BACDB', 'valign' => 'center', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado)->addText('Exposición', $encabezado_texto);
                $table->addCell($col_12, array('bgColor' => '#0BACDB', 'valign' => 'center', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado)->addText('Consecuencia', $encabezado_texto);
                $table->addCell($col_13, $continua_fila);


                foreach ($condicion_insegura as $key => $value) 
                {
                    $table->addRow(200);
                    // $table->addCell($col_1, array('valign' => 'center', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado)->addText($value->reportearea->reportearea_nombre, $texto);
                    // $table->addCell($col_2, array('valign' => 'center', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_actividad, $texto);
                    $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_actividad, $texto);
                    $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_rutinario, $texto);
                    $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_descripcion, $texto);
                    $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_clasificacion, $texto);
                    $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_efectos, $texto);
                    $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_fuente, $texto);
                    $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_medio, $texto);
                    $table->addCell($col_9, $celda)->addTextRun($centrado)->addText($value->categorias, $texto);
                    $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_probabilidad, $texto);
                    $table->addCell($col_11, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_exposicion, $texto);
                    $table->addCell($col_12, $celda)->addTextRun($centrado)->addText($value->reporteserviciopersonalcondicioninsegura_consecuencia, $texto);
                    $table->addCell($col_13, array('valign' => 'center', 'bgColor' => $value->resultado_color))->addTextRun($centrado)->addText($value->resultado, array('color' => $value->resultado_colortext, 'size' => $font_size, 'bold' => true, 'name' => $fuente));
                }


                $plantillaword->setComplexBlock('CONDICION_INSEGURA_T2', $table);


                //------------------------------------------------------------


                if (file_exists(public_path('/assets/images/reportes/serviciopersonal/img_10.jpg')))
                {
                    $plantillaword->setImageValue('CONDICION_IMG', array('path' => public_path('/assets/images/reportes/serviciopersonal/img_10.jpg'), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('CONDICION_IMG', '');
                }
            }
            else
            {
                $txt = '<w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:p>
                            <w:rPr>
                                <w:b w:val="true"/>
                                <w:t xml:space="preserve">Nota aclaratoria:</w:t>
                            </w:rPr>
                        </w:p>';

                $txt .= '<w:br/><w:p>
                            <w:rPr>
                                <w:jc w:val="end"/>
                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                <w:b w:val="false"/>
                                <w:t xml:space="preserve">En las áreas del '.$reporte->reporteserviciopersonal_instalacion.' no se identificaron condiciones inseguras, por consecuencia no puede ser valorado en este apartado.</w:t>
                            </w:rPr>
                        </w:p>';

                $plantillaword->setValue('CONDICION_INSEGURA_T1', $txt);
                $plantillaword->setValue('CONDICION_SALTOLINEA', '');
                $plantillaword->setValue('CONDICION_INSEGURA_T2', '');
                $plantillaword->setValue('CONDICION_IMG', '');
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
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%'.$agente_nombre.'%"');


            $planoscarpetasvPoppinses = ''; $planocontador = 0; $plano_archivo = array();
            if (count($planoscarpetas) > 0)
            {
                foreach ($planoscarpetas as $key_c => $carpeta)
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
                                                AND proyectoevidenciaplano.agente_nombre LIKE "%'.$carpeta->agente_nombre.'%" 
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = "'.$carpeta->reporteplanoscarpetas_nombre.'" 
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo ASC');


                    foreach ($planos as $key_p => $plano)
                    {
                        if (($key_c+0) == (count($planoscarpetas)-1) && ($key_p+0) == (count($planos)-1)) // ultima carpeta y ultimo plano
                        {
                            $planoscarpetasvPoppinses .= '${PLANO_'.$planocontador.'_FOTO}';
                        }
                        else
                        {
                            $planoscarpetasvPoppinses .= '${PLANO_'.$planocontador.'_FOTO}<w:pPr><w:br w:type="page" /></w:pPr>';
                        }


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


            // EVALUACION FOTOS
            //================================================================================


            if (count($evaluacion) > 0)
            {
                foreach ($evaluacion as $key => $value) 
                {
                    if ($value->reporteserviciopersonalevaluacion_evidencia1)
                    {
                        if (file_exists(storage_path('app/'.$value->reporteserviciopersonalevaluacion_evidencia1)))
                        {
                            $plantillaword->setImageValue('EVAL'.$value->id.'_EVI_1', array('path' => storage_path('app/'.$value->reporteserviciopersonalevaluacion_evidencia1), 'width' => 180, 'height' => 180, 'ratio' => false, 'borderColor' => '000000'));
                        }
                        else
                        {
                            $plantillaword->setValue('EVAL'.$value->id.'_EVI_1', 'SIN EVIDENCIA 1');
                        }
                    }
                    else
                    {
                        $plantillaword->setValue('EVAL'.$value->id.'_EVI_1', '');
                    }


                    if ($value->reporteserviciopersonalevaluacion_evidencia2)
                    {
                        if (file_exists(storage_path('app/'.$value->reporteserviciopersonalevaluacion_evidencia2)))
                        {
                            $plantillaword->setImageValue('EVAL'.$value->id.'_EVI_2', array('path' => storage_path('app/'.$value->reporteserviciopersonalevaluacion_evidencia2), 'width' => 180, 'height' => 180, 'ratio' => false, 'borderColor' => '000000'));
                        }
                        else
                        {
                            $plantillaword->setValue('EVAL'.$value->id.'_EVI_2', 'SIN EVIDENCIA 2');
                        }
                    }
                    else
                    {
                        $plantillaword->setValue('EVAL'.$value->id.'_EVI_2', '');
                    }
                }
            }


            // ANEXO CONDICION INSEGURA DETECTADA
            //================================================================================


            if (count($condicion_insegura) > 0)
            {
                foreach ($condicion_insegura as $key => $value) 
                {
                    if ($value->reporteserviciopersonalcondicioninsegura_foto1)
                    {
                        if (file_exists(storage_path('app/'.$value->reporteserviciopersonalcondicioninsegura_foto1)))
                        {
                            $plantillaword->setImageValue('COND'.$value->id.'_EVI_1', array('path' => storage_path('app/'.$value->reporteserviciopersonalcondicioninsegura_foto1), 'width' => 180, 'height' => 180, 'ratio' => false, 'borderColor' => '000000'));
                        }
                        else
                        {
                            $plantillaword->setValue('COND'.$value->id.'_EVI_1', 'SIN EVIDENCIA 1');
                        }
                    }
                    else
                    {
                        $plantillaword->setValue('COND'.$value->id.'_EVI_1', '');
                    }


                    if ($value->reporteserviciopersonalcondicioninsegura_foto2)
                    {
                        if (file_exists(storage_path('app/'.$value->reporteserviciopersonalcondicioninsegura_foto2)))
                        {
                            $plantillaword->setImageValue('COND'.$value->id.'_EVI_2', array('path' => storage_path('app/'.$value->reporteserviciopersonalcondicioninsegura_foto2), 'width' => 180, 'height' => 180, 'ratio' => false, 'borderColor' => '000000'));
                        }
                        else
                        {
                            $plantillaword->setValue('COND'.$value->id.'_EVI_2', 'SIN EVIDENCIA 2');
                        }
                    }
                    else
                    {
                        $plantillaword->setValue('COND'.$value->id.'_EVI_2', '');
                    }
                }
            }


            // ANEXO 3, Plano de ubicación de proliferación y/o focos de desarrollo y anidación de fauna nociva
            //================================================================================


            for ($i = 0; $i < count($plano_archivo); $i ++)
            {
                if (Storage::exists($plano_archivo[$i]))
                {
                    $plantillaword->setImageValue('PLANO_'.$i.'_FOTO', array('path' => storage_path('app/'.$plano_archivo[$i]), 'height' => 690, 'width' => 588, 'ratio' => false, 'borderColor' => '#000000'));
                }
                else
                {
                    $plantillaword->setValue('PLANO_'.$i.'_FOTO', 'NO SE ENCONTRÓ EL PLANO');
                }
            }


            // ANEXOS PDF's
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
                                                        AND reporteanexos.agente_nombre = "'.$agente_nombre.'"
                                                )
                                            ) AS ANEXO');


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL


            $informe_nombre = 'Informe de '.$agente_nombre.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.').docx';


            // GUARDAR WORD FINAL
            $plantillaword->saveAs(storage_path('app/reportes/informes/'.$informe_nombre)); //crear archivo word

            
            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx'))
            {
                Storage::delete('reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx');
            }


            //--------------------------------------------------------------------------------


            if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
            {
                //================================================================================
                // CREAR .ZIP INFORME


                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');
                // Zip File Name
                $zip_nombre = 'Informe de '.$agente_nombre.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';
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
                $zip_nombre = 'Informe de '.$agente_nombre.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';
                
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
                    , 'reporterevisionesarchivo_tipo' => 0
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
                                                AND reporterevisiones.agente_id = '.$request->agente_id.' -- Infraestructura de servicios al personal
                                            ORDER BY
                                                reporterevisiones.reporterevisiones_revision DESC');


                // CREAR NUEVA REVISION
                // -------------------------------------------------


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
     * @param  int $ultima_revision
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Infraestructura para Servicios al Personal';


        if (($revision_id+0) == ($ultima_revision+0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $revision = reporterevisionesModel::findOrFail($revision_id);

            $zip_nombre = 'Informe de '.$agente_nombre.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';


            if (Storage::exists('reportes/informes/'.$zip_nombre))
            {
                return response()->download(storage_path('app/reportes/informes/'.$zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
            }
            else
            {
                return '<h3>No se encontró el informe de '.$agente_nombre.', intentelo de nuevo</h3>';
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
                                                reporterevisiones.id = '.$revision_id);


            if (count($archivo_historial) > 0)
            {
                if (Storage::exists($archivo_historial[0]->reporterevisionesarchivo_archivo))
                {
                    return response()->download(storage_path('app/'.$archivo_historial[0]->reporterevisionesarchivo_archivo), "", array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(false);
                }
                else
                {
                    return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.'</h3>';
                }
            }
            else
            {
                return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.'</h3>';
            }
        }
    }
}
