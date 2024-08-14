<?php

namespace App\Http\Controllers\recsensorialreportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use PhpOffice\PhpWord;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use DB;
use ZipArchive;

// Modelos
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\clientes\clienteModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\recsensorial\recsensorialRecursosInformesModel;



class recsensorialquimicosreportewordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }


    public function ejemploCargarDocx()
    {

        $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Portada.docx')); //Ruta carpeta storage

        $plantillaword->setValue('folio', 'FOLIO DE PRUEBA');
        $plantillaword->setValue('razon_social', 'RAZON SOCIAL DE PRUEBA');
        $plantillaword->setValue('instalacion', 'INTALACION SOCIAL DE PRUEBA');
        $plantillaword->setValue('lugar_fecha', 'LUGAR Y FECHA DE PRUEBA');
        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/plantillas_reportes/FOTO.png'), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));


        Storage::makeDirectory('reportes/ejemplo'); //crear directorio
        $plantillaword->saveAs(storage_path('app/reportes/ejemplo/Ejemplo.docx')); //crear archivo word

        return response()->download(storage_path('app/reportes/ejemplo/Ejemplo.docx'))->deleteFileAfterSend(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosreporte1word($recsensorial_id)
    {
        $No = 1;

        // Datos reconocimiento sensorial
        // $recsensorial = DB::select('SET lc_time_names = "es_MX"');
        $recsensorial = DB::select('SELECT
                                        recsensorial.id,
                                        recsensorial.recsensorial_foliofisico,
                                        recsensorial.recsensorial_folioquimico,
                                        cliente.id AS cliente_id,
                                        cc.NUMERO_CONTRATO as cliente_numerocontrato,
                                        catregion.catregion_nombre,
                                        catgerencia.catgerencia_nombre,
                                        catactivo.catactivo_nombre,
                                        recsensorial.contrato_id,
                                        recsensorial.recsensorial_empresa,
                                        recsensorial.recsensorial_codigopostal,
                                        recsensorial.recsensorial_rfc,
                                        recsensorial.recsensorial_representantelegal,
                                        recsensorial.recsensorial_representanteseguridad,
                                        recsensorial.recsensorial_instalacion,
                                        recsensorial.recsensorial_direccion,
                                        recsensorial.recsensorial_coordenadas,
                                        recsensorial.recsensorial_repquimicos1nombre,
                                        recsensorial.recsensorial_repquimicos1cargo,
                                        recsensorial.recsensorial_repquimicos1doc,
                                        recsensorial.recsensorial_repquimicos2nombre,
                                        recsensorial.recsensorial_repquimicos2cargo,
                                        recsensorial.recsensorial_repquimicos2doc,
                                        CONCAT(catsubdireccion.catsubdireccion_siglas," - ", catsubdireccion.catsubdireccion_nombre) as subdireccion,
                                        CONCAT((
                                            CASE
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "01" THEN "Enero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "02" THEN "Febrero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "03" THEN "Marzo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "04" THEN "Abril"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "05" THEN "Mayo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "06" THEN "Junio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "07" THEN "Julio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "08" THEN "Agosto"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "09" THEN "Septiembre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "10" THEN "OCtubre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "11" THEN "Noviembre"
                                                ELSE "Diciembre"
                                            END
                                        ), " ",DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y")) AS fecha_elaboracion,
                                        CONCAT(DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%d")," ", (
                                            CASE
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "01" THEN "Enero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "02" THEN "Febrero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "03" THEN "Marzo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "04" THEN "Abril"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "05" THEN "Mayo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "06" THEN "Junio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "07" THEN "Julio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "08" THEN "Agosto"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "09" THEN "Septiembre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "10" THEN "OCtubre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%m") = "11" THEN "Noviembre"
                                                ELSE "Diciembre"
                                            END
                                        ), " ",DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y")) AS fechainicio_texto,
                                        CONCAT(DATE_FORMAT(recsensorial.recsensorial_fechafin, "%d")," ", (
                                            CASE
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "01" THEN "Enero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "02" THEN "Febrero"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "03" THEN "Marzo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "04" THEN "Abril"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "05" THEN "Mayo"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "06" THEN "Junio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "07" THEN "Julio"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "08" THEN "Agosto"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "09" THEN "Septiembre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "10" THEN "OCtubre"
                                                WHEN DATE_FORMAT(recsensorial.recsensorial_fechafin, "%m") = "11" THEN "Noviembre"
                                                ELSE "Diciembre"
                                            END
                                        ), " ",DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y")) AS fechafin_texto,
                                        DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%d-%m-%Y") AS recsensorial_fechainicio,
                                        DATE_FORMAT(recsensorial.recsensorial_fechafin, "%d-%m-%Y") AS recsensorial_fechafin,
                                        recsensorial.recsensorial_actividadprincipal,
                                        recsensorial.recsensorial_descripcionproceso,
                                        recsensorial.recsensorial_fotoubicacion,
                                        recsensorial.recsensorial_fotoplano,
                                        recsensorial.recsensorial_obscategorias,
                                        recsensorial.recsensorial_elabora1,
                                        recsensorial.recsensorial_elabora2,
                                        recsensorial.recsensorial_eliminado ,
                                        info.PETICION_CLIENTE
                                    FROM
                                        recsensorial
                                        LEFT JOIN cliente ON recsensorial.cliente_id = cliente.id
                                        LEFT JOIN catregion ON recsensorial.catregion_id = catregion.id
                                        LEFT JOIN catgerencia ON recsensorial.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON recsensorial.catactivo_id = catactivo.id 
                                        LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = recsensorial.contrato_id
                                        LEFT JOIN catsubdireccion ON recsensorial.catsubdireccion_id = catsubdireccion.id
                                        LEFT JOIN recsensorial_recursos_informes info ON info.RECSENSORIAL_ID = recsensorial.id
                                    WHERE
                                        recsensorial.id = ' . $recsensorial_id);
        // echo $recsensorial[0]->recsensorial_descripcionproceso;

        // LEER PLANTILLA WORD
        //================================================================================


        // $plantillaword = new TemplateProcessor(public_path('/plantillas_reportes/reconocimiento_sensorial/Informe_de_reconocimiento_quimicos.docx'));  //Ruta carpeta public
        $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_quimicos_nuevo.docx')); //Ruta carpeta storage


        // ESCRIBIR DATOS GENERALES
        //================================================================================

        $cliente = clienteModel::findOrFail($recsensorial[0]->cliente_id);
        $contrato = clientecontratoModel::findOrFail($recsensorial[0]->contrato_id);
        $recursos = recsensorialRecursosInformesModel::where('RECSENSORIAL_ID', $recsensorial[0]->id)->get();
        $numeros = DB::select('SELECT COUNT(DISTINCT catsustancia_id) AS total_catsustancias
                                    FROM recsensorialquimicosinventario
                                    WHERE recsensorial_id = ?', [$recsensorial_id]);


        //IMAGEN DE LA PORTADA
        if ($recursos[0]->IMAGEN_PORTADA) {
            if (file_exists(storage_path('app/' . $recursos[0]->IMAGEN_PORTADA))) {

                $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
            } else {

                $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
            }
        } else {

            $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO SE HA CREADO');
        }


        //LOGOS DE AS EMPRESAS DE INFORME
        if ($contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
            if (file_exists(storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {
                // $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 180, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
            } else {

                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                // $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
            }
        } else {
            // $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
        }


        if ($contrato->CONTRATO_PLANTILLA_LOGODERECHO) {
            if (file_exists(storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGODERECHO))) {
                // $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 180, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $contrato->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
            } else {

                $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                // $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
            }
        } else {
            // $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
        }



        $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial[0]->contrato_id)
            ->where('clientepartidas_tipo', 1) // reconocimiento
            ->where('catprueba_id', 2) // quimicos
            ->orderBy('updated_at', 'DESC')
            ->get();

        //PARTE DEL PROYECTO
        if (count($titulo_partida) > 0) {

            //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
            $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $recsensorial[0]->cliente_numerocontrato);
        } else {
            $plantillaword->setValue('proyecto_portada', 'INFORMACIÓN NO CARGADA EN EL SOFTWARE');
        }

        //PARTE DEL FOLIO PORTADA
        $plantillaword->setValue('folio_portada', $recsensorial[0]->recsensorial_folioquimico);

        //PARTE DE REALIZADO PARA PORTADA
        $plantillaword->setValue('razon_social_portada', $recsensorial[0]->recsensorial_empresa);

        // PARTE INTALACION PORTADA
        $OPCION_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . " | ";
        $OPCION_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . " | ";
        $OPCION_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . " | ";
        $OPCION_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . " | ";
        $OPCION_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . " | ";
        $OPCION_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6;

        $plantillaword->setValue('instalación_portada', $OPCION_PORTADA1 . $OPCION_PORTADA2 . $OPCION_PORTADA3 . $OPCION_PORTADA4 . $OPCION_PORTADA5 . $OPCION_PORTADA6);


        //PARTE DE LUGAR Y FECHA PORTADA
        $plantillaword->setValue('lugar_fecha_portada', $recsensorial[0]->recsensorial_direccion . ' ' . $recsensorial[0]->fecha_elaboracion);


        //ENCABEZADOS TITULOS
        $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
        $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
        $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
        $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
        $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

        $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);




        //INTRODUCCION
        $plantillaword->setValue('introduccion', str_replace("\n", "<w:br/>", $recursos[0]->INTRODUCCION));

        //METODOLOGIA
        $plantillaword->setValue('metodologia', $recursos[0]->METODOLOGIA);

        //PIE DE PAGINA DE LAS HOJAS
        $plantillaword->setValue('PIE_PAGINA', str_replace("\n", "<w:br/>", $contrato->CONTRATO_PLANTILLA_PIEPAGINA));

        //DATOS DEL CENTRO DE TRABAJO
        $plantillaword->setValue('instalación', $recsensorial[0]->recsensorial_instalacion);
        $plantillaword->setValue('empresa_nombre', $recsensorial[0]->recsensorial_empresa);
        $plantillaword->setValue('empresa_rfc', $recsensorial[0]->recsensorial_rfc);
        $plantillaword->setValue('empresa_codigopostal', $recsensorial[0]->recsensorial_codigopostal);
        $plantillaword->setValue('empresa_representantelegal', $recsensorial[0]->recsensorial_representantelegal);
        $plantillaword->setValue('empresa_representanteseguridad', $recsensorial[0]->recsensorial_representanteseguridad);
        $plantillaword->setValue('actividad_principal', $recsensorial[0]->recsensorial_actividadprincipal);
        $plantillaword->setValue('direccion', $recsensorial[0]->recsensorial_direccion);


        //DESCRIPCION DEL PROCESO
        $text = $recsensorial[0]->recsensorial_descripcionproceso;
        $parrafos = explode("\n\n", $text);
        $texto = '';


        foreach ($parrafos as $key => $parrafo) {
            if (($key + 0) < (count($parrafos) - 1)) {
                $text = explode("\n", $parrafo);

                foreach ($text as $key2 => $parrafo2) {
                    if (($key2 + 0) < (count($text) - 1)) {
                        $texto .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>' . htmlspecialchars($parrafo2) . '</w:t>
                                        </w:p>';
                    } else {
                        $texto .= '<w:p>
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
                        $texto .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>' . htmlspecialchars($parrafo2) . '</w:t>
                                        </w:p>';
                    } else {
                        $texto .= '<w:t>' . htmlspecialchars($parrafo2) . '</w:t>';
                    }
                }
            }
        }
        $plantillaword->setValue('descripción_proceso', $texto);


        // $plantillaword->setValue('fecha', $recsensorial[0]->fechainicio_texto.' al '.$recsensorial[0]->fechafin_texto);
        // $plantillaword->setValue('fecha_elaboracion', $recsensorial[0]->fecha_elaboracion);
        // $plantillaword->setValue('elabora', $recsensorial[0]->recsensorial_elabora2);




        // FOTO PLANO
        if ($recsensorial[0]->recsensorial_fotoplano) {
            if (file_exists(storage_path('app/' . $recsensorial[0]->recsensorial_fotoplano))) {
                $plantillaword->setImageValue('foto_plano', array('path' => storage_path('app/' . $recsensorial[0]->recsensorial_fotoplano), 'height' => 650, 'width' => 650, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('foto_plano', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }
        } else {
            $plantillaword->setValue('foto_plano', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
        }


        // DISEÑO TABLAS
        //================================================================================

        // formato celdas
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



        // TABLA 4 AREAS, PROCESOS Y PUESTOS DE TRABAJO OBJETO DEL RECONOCIMIENTO
        //================================================================================
        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT a.recsensorialarea_nombre AREA, a.RECSENSORIALAREA_PROCESO PROCESO, c.recsensorialcategoria_nombrecategoria CATEGORIA, ac.recsensorialareacategorias_total PERSONAS
        FROM recsensorialareacategorias ac
        LEFT JOIN recsensorialarea a ON a.id = ac.recsensorialarea_id
        LEFT JOIN recsensorialcategoria c ON c.id = ac.recsensorialcategoria_id
        WHERE a.recsensorial_id = ? ', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Proceso', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Puesto de trabajo', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Número de <w:br /> trabajadores <w:br /> expuestos ', $encabezado_texto);

        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {

            //SI EL AREA ES EL MISMO CREAMOS UNA NUEVA FILA PERO SIN PONER EL NOMBRE DEL AREA (OSEA COMBINAMOS LA FILA)
            if ($area != $value->AREA) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                $table->addCell(null, $combinar_fila)->addTextRun($justificado)->addText($value->PROCESO, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PERSONAS, $texto);

                $area = $value->AREA;
            } else {
                //SI EL AREA CAMBIA AGREGAMOS UNA NUEVA TABLA CON EL NOMBRE (OSEA CREAMOS UNA NUEVA FILA)
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PERSONAS, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_areas', $table);



        // TABLA 5.1 TABLA DE LISTADO E INVENTARIO DE PRODUCTOS Y/O SUSTANCIAS QUIMICAS
        //================================================================================
        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                            recsensorialquimicosinventario.recsensorial_id,
                            -- recsensorialquimicosinventario.id,
                            IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                            IFNULL(catsustancia.catsustancia_nombre, "Sin dato") AS catsustancia_nombre,
                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                            catestadofisicosustancia.catestadofisicosustancia_estado,
                            catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                            catsustancia.catClasificacionRiesgo,
                            catsustancia.id
                        FROM
                            recsensorialquimicosinventario
                            LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                            LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                            LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                            LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                            LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id
                            LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id 
                        WHERE
                            recsensorialquimicosinventario.recsensorial_id = ?
                        GROUP BY
                            recsensorialquimicosinventario.recsensorial_id,
                            -- recsensorialquimicosinventario.id,
                            recsensorialarea.id,
                            recsensorialarea.recsensorialarea_nombre,
                            catsustancia.id,
                            catsustancia.catsustancia_nombre,
                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                            catestadofisicosustancia.catestadofisicosustancia_estado,
                            catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                            catsustancia.catgradoriesgosalud_id,
                            catgradoriesgosalud.catgradoriesgosalud_clasificacion,
                            catsustancia.catClasificacionRiesgo
                        ORDER BY
                            recsensorialarea.id ASC,
                            catsustancia.id ASC', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Sustancia química <w:br /> y/o producto', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad manejada por jornada de trabajo', $encabezado_texto);
        $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Estado físico del<w:br />producto y/o sustancia', $encabezado_texto);
        // $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Vía(s) de ingreso<w:br />al organismo', $encabezado_texto);
        // $table->addCell(2250, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación de riesgo<w:br />a la salud', $encabezado_texto);

        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialquimicosinventario_cantidad . " " . $value->catunidadmedidasustacia_abreviacion, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                // $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                // $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catClasificacionRiesgo , $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialquimicosinventario_cantidad . " " . $value->catunidadmedidasustacia_abreviacion, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                // $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                // $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catClasificacionRiesgo, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_quimicos_inventario', $table);



        function sanitizeText($text)
        {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }


        // TABLA 6.1 TABLA DE CARACTERISTICAS FISICAS DE LAS SUSTANCIAS QUIMICAS
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('CALL sp_obtener_caracteristicas_sustancia_informe_b(?)', [$recsensorial_id]);


        $productos = [];
        $counter = 1;
        $lastName = null;

        foreach ($sql as $key => $val) {
            $currentName = $val->catsustancia_nombre;

            if ($currentName !== $lastName) {
                $productos[$currentName] = $counter;
                $counter++;
                $lastName = $currentName;
            }
        }


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3150, $encabezado_celda)->addTextRun($centrado)->addText('Sustancia química <w:br /> y/o producto', $encabezado_texto);
        $table->addCell(3150, $encabezado_celda)->addTextRun($centrado)->addText('Componentes<w:br />de la mezcla', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Número CAS', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Temperatura de ebullición (°C)', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de componente (%)', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Peso Molecular (gr/mol)', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Estado físico', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Volatilidad', $encabezado_texto);

        // registros tabla
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila

            if ($sustancia != $value->catsustancia_nombre) {
                $table->addCell(3150, $combinar_fila)->addTextRun($centrado)->addText('[' . $productos[$value->catsustancia_nombre] . '] ' . sanitizeText($value->catsustancia_nombre), $texto);
                $table->addCell(3150, $celda)->addTextRun($centrado)->addText(sanitizeText($value->SUSTANCIA_QUIMICA), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->NUM_CAS), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->TEM_EBULLICION), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->OPERADOR . $value->PORCENTAJE), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PM), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->catestadofisicosustancia_estado), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->catvolatilidad_tipo), $texto);

                $sustancia = $value->catsustancia_nombre;
            } else {
                $table->addCell(3150, $continua_fila);
                $table->addCell(3150, $celda)->addTextRun($centrado)->addText(sanitizeText($value->SUSTANCIA_QUIMICA), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->NUM_CAS), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->TEM_EBULLICION), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->OPERADOR . $value->PORCENTAJE), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PM), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->catestadofisicosustancia_estado), $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText(sanitizeText($value->catvolatilidad_tipo), $texto);
            }
        }

        $plantillaword->setComplexBlock('tabla_quimicos_mezclas', $table);




        // TABLA 6.2 GRADO DE RIESGO Y PELIGRO A LA SALUD
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select("SELECT hoja.catsustancia_nombre,
                                        sus.SUSTANCIA_QUIMICA,
                                        ingreso.catviaingresoorganismo_viaingreso AS VIA_INGRESO,
                                        sus.CLASIFICACION_RIESGO,
                                        IFNULL(entidad.VLE_PPT, 'ND') AS PPT,
                                        IFNULL(entidad.VLE_CT_P, 'ND') AS CT,
                                        relacion.PORCENTAJE
                                FROM recsensorialquimicosinventario inventario
                                LEFT JOIN catHojasSeguridad_SustanciasQuimicas relacion ON relacion.HOJA_SEGURIDAD_ID = inventario.catsustancia_id
                                LEFT JOIN catsustancia hoja ON hoja.id = relacion.HOJA_SEGURIDAD_ID
                                LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = relacion.SUSTANCIA_QUIMICA_ID
                                LEFT JOIN sustanciaQuimicaEntidad entidad ON entidad.SUSTANCIA_QUIMICA_ID = sus.ID_SUSTANCIA_QUIMICA
                                LEFT JOIN catEntidades catEntidad ON catEntidad.ID_ENTIDAD = entidad.ENTIDAD_ID
                                LEFT JOIN catviaingresoorganismo ingreso ON ingreso.id = sus.VIA_INGRESO
                                WHERE inventario.recsensorial_id = ?
                                    AND entidad.ENTIDAD_ID = 1 
                                    AND  (relacion.PORCENTAJE > 1.00 OR (JSON_CONTAINS(entidad.CONNOTACION, '\"1\"') OR JSON_CONTAINS(entidad.CONNOTACION, '\"2\"')))
                                GROUP BY relacion.HOJA_SEGURIDAD_ID,
                                        relacion.SUSTANCIA_QUIMICA_ID,
                                        hoja.catsustancia_nombre, 
                                        sus.SUSTANCIA_QUIMICA,
                                        ingreso.catviaingresoorganismo_viaingreso,
                                        sus.CLASIFICACION_RIESGO,
                                        PPT,
                                        CT,
                                        relacion.PORCENTAJE
                                ORDER BY  hoja.id ASC ", [$recsensorial_id]);
        //OBTENEMOS TODAS AQUELLOS COMPONENTES LOS CUALES SEAN MAYOR AL 1% PERO SIEMPRE Y CUANDO NO TENGAN CONNOTACION CANCERIGENA


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(2700, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Sustancia química <w:br /> y/o producto', $encabezado_texto);
        $table->addCell(2700, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Componentes<w:br />de la mezcla', $encabezado_texto);
        $table->addCell(2700, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Vía de ingreso<w:br />al organismo', $encabezado_texto);
        $table->addCell(2700, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Grado de riesgo<w:br />a la salud', $encabezado_texto);
        $table->addCell(2700, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '1A5276'))->addTextRun($centrado)->addText('Valores límite<w:br />de exposición', $encabezado_texto); // combina columna
        $table->addRow(); //fila
        $table->addCell(2700, $continua_fila);
        $table->addCell(2700, $continua_fila);
        $table->addCell(2700, $continua_fila);
        $table->addCell(2700, $continua_fila);
        $table->addCell(1350, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell(1350, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);

        // registros tabla
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            if ($sustancia != $value->catsustancia_nombre) {
                $table->addRow(); //fila
                $table->addCell(2700, $combinar_fila)->addTextRun($centrado)->addText('[' . $productos[$value->catsustancia_nombre] . '] ' . $value->catsustancia_nombre, $texto);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->SUSTANCIA_QUIMICA, $texto);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->VIA_INGRESO, $texto);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->CLASIFICACION_RIESGO, $texto);
                $table->addCell(1350, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                $table->addCell(1350, $celda)->addTextRun($centrado)->addText($value->CT, $texto);

                $sustancia = $value->catsustancia_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(2700, $continua_fila);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->SUSTANCIA_QUIMICA, $texto);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->VIA_INGRESO, $texto);
                $table->addCell(2700, $celda)->addTextRun($centrado)->addText($value->CLASIFICACION_RIESGO, $texto);
                $table->addCell(1350, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                $table->addCell(1350, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
            }
        }

        $plantillaword->setComplexBlock('tabla_quimicos_gradoriesgo', $table);



        // TABLA 7.2 TABLA FUENTES GENERADORAS
        //================================================================================
        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                            recsensorialmaquinaria.recsensorial_id,
                            recsensorialmaquinaria.recsensorialarea_id,
                            sus.catsustancia_nombre AS agente,
                            IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                            recsensorialmaquinaria.id,
                            recsensorialmaquinaria.recsensorialmaquinaria_descripcionfuente AS recsensorialmaquinaria_nombre,
                            IF(recsensorialmaquinaria.recsensorialmaquinaria_unidadMedida = 7,
                                CONCAT(recsensorialmaquinaria.recsensorialmaquinaria_cantidad, " PZ"),
                                CONCAT(recsensorialmaquinaria.recsensorialmaquinaria_cantidad, " de ", recsensorialmaquinaria.recsensorialmaquinaria_contenido , " " , 
                                    CASE recsensorialmaquinaria.recsensorialmaquinaria_unidadMedida
                                        WHEN 1 THEN "MM"
                                        WHEN 2 THEN "L"
                                        WHEN 3 THEN "M³"
                                        WHEN 4 THEN "G"
                                        WHEN 5 THEN "Kl"
                                        WHEN 6 THEN "T"
                                        ELSE "ND"
                                    END
                                )
                            ) AS recsensorialmaquinaria_cantidad,
                            recsensorialmaquinaria.recsensorialmaquinaria_afecta 
                        FROM
                            recsensorialmaquinaria
                            LEFT JOIN recsensorialarea ON recsensorialmaquinaria.recsensorialarea_id = recsensorialarea.id 
                            LEFT JOIN catsustancia sus ON sus.id = recsensorialmaquinaria.recsensorialmaquinaria_quimica
                        WHERE
                            recsensorialmaquinaria.recsensorial_id = ?
                            AND (recsensorialmaquinaria.recsensorialmaquinaria_afecta = 2 OR recsensorialmaquinaria.recsensorialmaquinaria_afecta = 3) 
                        ORDER BY
                            recsensorialarea.id ASC,
                            recsensorialmaquinaria.recsensorialmaquinaria_nombre ASC;', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(5000, $encabezado_celda)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
        $table->addCell(5000, $encabezado_celda)->addTextRun($centrado)->addText('Agentes químicos identificados', $encabezado_texto);
        $table->addCell(6000, $encabezado_celda)->addTextRun($centrado)->addText('Fuente generadora', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);

        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {

            // $agentes = DB::select('SELECT
            //                             IFNULL(CONCAT("● ", REPLACE(GROUP_CONCAT(TABLA.catsustancia_nombre), ",", "<w:br />● ")), "Sin dato") AS agentes_quimicos
            //                         FROM
            //                             (
            //                                 SELECT  
            //                                     catsustancia.catsustancia_nombre
            //                                 FROM
            //                                     recsensorialquimicosinventario
            //                                     LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id 
            //                                 WHERE
            //                                     recsensorialquimicosinventario.recsensorialarea_id = ?
            //                                 GROUP BY
            //                                     catsustancia.catsustancia_nombre
            //                             ) AS TABLA', [$value->recsensorialarea_id]);


            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->agente, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->agente, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);
            }
        }

        $plantillaword->setComplexBlock('tabla_quimicos_maquinaria', $table);




        // TABLA 7.3 TABLA CONDICIONES Y CARACTERISTICAS DE AREA DE PROCESO
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                recsensorialarea.recsensorial_id,
                                recsensorialarea.id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                recsensorialarea.recsensorialarea_condicion,
                                recsensorialarea.recsensorialarea_caracteristica,
                                recsensorialarea.recsensorialarea_extraccionaire,
                                recsensorialarea.recsensorialarea_inyeccionaire,
                                IF(REPLACE(recsensorialarea.recsensorialarea_generacioncontaminante,",", ", ") = "N/A", "Se realiza actividades administrativas, por lo cual no tiene una descripción detallada de algún proceso industrial" ,REPLACE(recsensorialarea.recsensorialarea_generacioncontaminante,",", ", "))  AS recsensorialarea_generacioncontaminante
                            FROM
                                recsensorialarea 
                            WHERE
                                recsensorialarea.recsensorial_id = ?
                            ORDER BY
                                    recsensorialarea.id ASC', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(300, array('tblHeader' => true));
        $table->addCell(1600, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Condición del lugar<w:br />(Abierto/Cerrado)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de extracción de aire <w:br />(general/localizado)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de inyección de aire <w:br />(general/localizado)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Características del proceso<w:br />(Continuo/Intermitente)', $encabezado_texto);
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Condiciones del procesos', $encabezado_texto);

        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(1600, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
            $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_condicion, $texto);
            $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_extraccionaire, $texto);
            $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_inyeccionaire, $texto);
            $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_caracteristica, $texto);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_generacioncontaminante, $texto);
        }

        $plantillaword->setComplexBlock('tabla_quimicos_condicionesarea', $table);


        // TABLA 8.1 - (DETERMINACION DE LA PRIORIDAD DE MUESTREO DE LAS SUSTANCIAS QUIMICAS) PONDERACION-1
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select("CALL sp_ponderacion1_tabla8_1_b(?)", [$recsensorial_id]);


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Sustancia química <w:br /> y/o producto', $encabezado_texto);
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Componentes <w:br />de la mezcla', $encabezado_texto);
        $table->addCell(4500, array('gridSpan' => 3, 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Valor de ponderación', $encabezado_texto); // combina columna
        $table->addCell(1500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('TOTAL<w:br />(Suma de los valores de ponderación)', $encabezado_texto);
        $table->addCell(1500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Prioridad de muestreo<w:br />(Tabla 11)', $encabezado_texto);

        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad manejada<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación de riesgo<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Volatilidad<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);

        if (!empty($sql)) {


            // registros tabla
            $area = 'xxx';
            $sustancia = 'xxx';
            foreach ($sql as $key => $value) {
                if ($area != $value->AREA) {
                    $table->addRow(); // fila para el nuevo área
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText('[' . $productos[$value->PRODUCTO] . '] ' . $value->PRODUCTO, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->COMPONENTE, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CANTIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CLASIFICACION, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_VOLATILIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                    $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                    $area = $value->AREA;
                    $sustancia = $value->PRODUCTO;
                } else if ($sustancia != $value->PRODUCTO) {
                    $table->addRow(); // fila para el nuevo producto dentro del área actual
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText('[' . $productos[$value->PRODUCTO] . '] ' . $value->PRODUCTO, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->COMPONENTE, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CANTIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CLASIFICACION, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_VOLATILIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                    $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                    $sustancia = $value->PRODUCTO;
                } else {
                    $table->addRow(); // fila para el nuevo componente dentro del producto actual
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->COMPONENTE, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CANTIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_CLASIFICACION, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_VOLATILIDAD, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                    $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                }
            }

            $plantillaword->setComplexBlock('tabla_quimicos_resumen1', $table);
        } else {



            $plantillaword->setValue('tabla_quimicos_resumen1', 'Se cuenta con ' . $numeros[0]->total_catsustancias . ' productos con una proporción  de componentes que incluyen diferentes sustancias químicas. Según el análisis realizado, no es necesario muestrear las sustancias químicas presentes en los productos de la empresa, debido a que las prioridades de muestreo obtenidas son bajas o muy bajas, por lo que no existe riesgo de exposición a sustancias químicas para los trabajadores.');
        }



        // TABLA 10.1 - (DETERMINACION DE LOS GRUPOS DE EXPOSICION HOMOGENEA) PONDERACION 3
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('CALL sp_obtenerDeterminacionGEH_b(?) ', [$recsensorial_id]);


        // encabezado tabla
        // Encabezado de la tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Clasificación GEH', $encabezado_texto);
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText(' Componente<w:br />(Sustancia química y/o producto)', $encabezado_texto);
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Área/zona', $encabezado_texto);
        $table->addCell(3500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell(4500, array('gridSpan' => 3, 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Valor de ponderación', $encabezado_texto); // Combina columna
        $table->addCell(1500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('TOTAL<w:br />(Suma de los valores de ponderación)', $encabezado_texto);
        $table->addCell(1500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('Prioridad de muestreo<w:br />(Tabla 14)', $encabezado_texto);
        $table->addCell(1500, array('vMerge' => 'restart', 'bgColor' => '1A5276', 'valign' => 'center'))->addTextRun($centrado)->addText('No. de POE a considerar<w:br />(Tabla 15 - 16)', $encabezado_texto);

        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);

        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Vía(s) de ingreso<w:br />al organismo<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Número de<w:br />POE expuesto<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de<w:br />exposición<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);
        $table->addCell(null, $continua_fila);



        // registros tabla
        if (!empty($sql)) {
            $grupo = 'xxx';
            $sustancia = 'xxx';
            $area = 'xxx';
            foreach ($sql as $key => $value) {
                if ($grupo != $value->CLASIFICACION) {
                    // Si cambia de area, reinicia la categoria y sustancia
                    $sustancia = 'xxx';
                    $area = 'xxx';

                    $table->addRow(); //fila
                    $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->CLASIFICACION, $texto);

                    if ($sustancia != $value->SUSTANCIA_PRODUCTO) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA_PRODUCTO, $textonegrita);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);
                        }

                        $sustancia = $value->SUSTANCIA_PRODUCTO;
                    } else {
                        $table->addCell(2300, $continua_fila);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);
                        }
                    }

                    $grupo = $value->CLASIFICACION;
                } else {
                    $table->addRow(); //fila
                    $table->addCell(2300, $continua_fila);

                    if ($sustancia != $value->SUSTANCIA_PRODUCTO) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA_PRODUCTO, $textonegrita);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);

                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);
                        }

                        $sustancia = $value->SUSTANCIA_PRODUCTO;
                    } else {
                        $table->addCell(2300, $continua_fila);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_INGRESO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PONDERACION_EXPOSICION, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->SUMA_PONDERACIONES, $texto);
                            $table->addCell(null, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->NUM_POE, $texto);
                        }
                    }
                }
            };

            $plantillaword->setComplexBlock('tabla_quimicos_resumen2', $table);
        } else {

            $plantillaword->setValue('tabla_quimicos_resumen2', 'Se cuenta con ' . $numeros[0]->total_catsustancias . ' productos con una proporción  de componentes que incluyen diferentes sustancias químicas. Según el análisis realizado, no es necesario muestrear las sustancias químicas presentes en los productos de la empresa, debido a que las prioridades de muestreo obtenidas son bajas o muy bajas, por lo que no existe riesgo de exposición a sustancias químicas para los trabajadores.');
        }



        // TABLA 9.1 - (TABLA DE GRUPO DE EXPOSICION HOMOGENEA) PONDERACION 2
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('CALL sp_obtenerGEH_b(?) ', [$recsensorial_id]);


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación GEH', $encabezado_texto);
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Componente de la mezcla (Sustancia química <w:br /> y/o producto)', $encabezado_texto);
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de exposición (minutos)', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Frecuencia de exposición por jornada', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de exposición total (minutos)', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('Jornada de trabajo (horas)', $encabezado_texto);

        // registros tabla

        if (!empty($sql)) {
            $grupo = 'xxx';
            $sustancia = 'xxx';
            $area = 'xxx';
            foreach ($sql as $key => $value) {
                if ($grupo != $value->CLASIFICACION) {
                    // Si cambia de area, reinicia la categoria y sustancia
                    $sustancia = 'xxx';
                    $area = 'xxx';

                    $table->addRow(); //fila
                    $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->CLASIFICACION, $texto);

                    if ($sustancia != $value->SUSTANCIA_PRODUCTO) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA_PRODUCTO, $textonegrita);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);
                        }

                        $sustancia = $value->SUSTANCIA_PRODUCTO;
                    } else {
                        $table->addCell(2300, $continua_fila);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);
                        }
                    }

                    $grupo = $value->CLASIFICACION;
                } else {
                    $table->addRow(); //fila
                    $table->addCell(2300, $continua_fila);

                    if ($sustancia != $value->SUSTANCIA_PRODUCTO) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA_PRODUCTO, $textonegrita);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);

                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);
                        }

                        $sustancia = $value->SUSTANCIA_PRODUCTO;
                    } else {
                        $table->addCell(2300, $continua_fila);

                        if ($area != $value->AREA) {
                            $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);


                            $area = $value->AREA;
                        } else {
                            $table->addCell(2300, $continua_fila);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->POE, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_EXPO, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->FRECUENCIA, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TIEMPO_TOTAL, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->JORNADA_TRABAJO, $texto);
                        }
                    }
                }
            }

            $plantillaword->setComplexBlock('tabla_quimicos_resumen3', $table);
        } else {

            $plantillaword->setValue('tabla_quimicos_resumen3', 'Se cuenta con ' . $numeros[0]->total_catsustancias . ' productos con una proporción  de componentes que incluyen diferentes sustancias químicas. Según el análisis realizado, no es necesario muestrear las sustancias químicas presentes en los productos de la empresa, debido a que las prioridades de muestreo obtenidas son bajas o muy bajas, por lo que no existe riesgo de exposición a sustancias químicas para los trabajadores.');
        }




        // TABLA 10.2 TABLA DE ACTIVIDADES DEL PERSONAL EXPUESTO
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                recsensorialcategoria.id,
                                -- IFNULL(CONCAT(recsensorialareacategorias.recsensorialareacategorias_geh, ".- ", recsensorialcategoria.recsensorialcategoria_nombrecategoria), "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                recsensorialareacategorias.recsensorialareacategorias_actividad 
                            FROM
                                recsensorialarea
                                INNER JOIN recsensorialareacategorias ON recsensorialarea.id = recsensorialareacategorias.recsensorialarea_id
                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                            WHERE
                                recsensorialarea.recsensorial_id = ?
                            GROUP BY
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                recsensorialcategoria.id,
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                recsensorialareacategorias.recsensorialareacategorias_actividad
                            ORDER BY
                                recsensorialarea.id ASC,
                                recsensorialcategoria.id ASC', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Áreas evaluadas', $encabezado_texto);
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('GEH / categoría', $encabezado_texto);
        $table->addCell(7500, $encabezado_celda)->addTextRun($centrado)->addText('Actividad', $encabezado_texto);

        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_actividad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_actividad, $texto);
            }
        }

        $plantillaword->setComplexBlock('tabla_quimicos_actividadespersonal', $table);


        // TABLA 10.3 TABLA DE EQUIPO DE PROTECCION PERSONAL SUMINISTRADO
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                recsensorialequipopp.recsensorial_id,
                                recsensorialcategoria.id,
                                -- IFNULL(IF( recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", CONCAT( recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_funcioncategoria, ")" ) ), "Sin dato") AS categoria,
                                IFNULL(IF( recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", recsensorialcategoria.recsensorialcategoria_nombrecategoria), "Sin dato") AS categoria,
                                recsensorialequipopp.catpartecuerpo_id,
                                catpartecuerpo.catpartecuerpo_nombre,
                                des.CLAVE_EPP as recsensorialequipopp_descripcion 
                            FROM
                                recsensorialequipopp
                                LEFT JOIN recsensorialcategoria ON recsensorialequipopp.recsensorialcategoria_id = recsensorialcategoria.id
                                LEFT JOIN catpartecuerpo ON recsensorialequipopp.catpartecuerpo_id = catpartecuerpo.id 
                                LEFT JOIN catpartescuerpo_descripcion des ON des.ID_PARTESCUERPO_DESCRIPCION = recsensorialequipopp.catpartescuerpo_descripcion_id
                            WHERE
                                recsensorialequipopp.recsensorial_id = ? 
                            ORDER BY
                                recsensorialcategoria.id ASC,
                                recsensorialequipopp.catpartecuerpo_id ASC', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parte del cuerpo', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Equipo de protección personal básico proporcionado', $encabezado_texto);

        // registros tabla
        $categoria = 'xxx';
        foreach ($sql as $key => $value) {
            if ($categoria != $value->categoria) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->categoria, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catpartecuerpo_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialequipopp_descripcion, $texto);

                $categoria = $value->categoria;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catpartecuerpo_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialequipopp_descripcion, $texto);
            }
        }

        $plantillaword->setComplexBlock('tabla_quimicos_epp', $table);


        // TABLA 11.1 - PUNTOS DE MUESTREO POE - RESUMEN VERSION 1 [PUNTOS DE MUESTREOS (NORMA)]
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('CALL sp_puntos_de_muestreoPOE_informe_b(?)', [$recsensorial_id]);


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(4350, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Clasificación GEH', $encabezado_texto);
        $table->addCell(4350, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Componente de la mezcla (Sustancia química <w:br /> y/o producto)', $encabezado_texto);
        $table->addCell(4350, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell(3300, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '1A5276'))->addTextRun($centrado)->addText('Número de puntos por POE / punto a considerar', $encabezado_texto); // combina columna
        $table->addCell(1500, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Total puntos', $encabezado_texto);
        $table->addRow(); //fila
        $table->addCell(4350, $continua_fila);
        $table->addCell(4350, $continua_fila);
        $table->addCell(4350, $continua_fila);
        $table->addCell(1650, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell(1650, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);
        $table->addCell(1500, $continua_fila);

        // registros tabla
        if (!empty($sql)) {

            $grupo = 'xxx';
            $sustancia = 'xxx';
            foreach ($sql as $key => $value) {
                if ($grupo != $value->CLASIFICACION) {
                    $table->addRow(); // fila para el nuevo área
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->CLASIFICACION, $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->PRODUCTO_COMPONENTE, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);


                    $grupo = $value->CLASIFICACION;
                    $sustancia = $value->PRODUCTO_COMPONENTE;
                } else if ($sustancia != $value->PRODUCTO_COMPONENTE) {
                    $table->addRow(); // fila para el nuevo producto dentro del área actual
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->PRODUCTO_COMPONENTE, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);
                } else {
                    $table->addRow(); // fila para el nuevo componente dentro del producto actual
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CATEGORIA, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);
                }
            }

            $plantillaword->setComplexBlock('tabla_quimicos_resumen4-1', $table);
        } else {

            $plantillaword->setValue('tabla_quimicos_resumen4-1', 'Se cuenta con ' . $numeros[0]->total_catsustancias . ' productos con una proporción  de componentes que incluyen diferentes sustancias químicas. Según el análisis realizado, no es necesario muestrear las sustancias químicas presentes en los productos de la empresa, debido a que las prioridades de muestreo obtenidas son bajas o muy bajas, por lo que no existe riesgo de exposición a sustancias químicas para los trabajadores.');
        }





        // TABLA 13.1 Controles para agentes químicos con los que cuenta el área
        //================================================================================
        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                    area.recsensorial_id,
                                    area.id,
                                    IFNULL(area.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                    IFNULL(des.DESCRIPCION, "Sin dato") AS DESCRIPCION_AREA
                            FROM
                                recsensorialarea area 
                            LEFT JOIN cat_descripcionarea des ON des.ID_DESCRIPCION_AREA = area.DESCRIPCION_AREA
                            WHERE
                                area.recsensorial_id = ?
                            ORDER BY
                                    area.id ASC', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Descripción del área', $encabezado_texto);

        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->DESCRIPCION_AREA, $texto);
        }

        $plantillaword->setComplexBlock('tabla_quimicos_controles', $table);



        // TABLA 13 - TABLA DE RESULTADOS - RESUMEN VERSION 1 [PUNTOS DE MUESTREOS Y TIPO DE INSTALACION (NORMA)]
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('CALL sp_obtener_puntos_finales_b(?)', [$recsensorial_id]);

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3450, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Componente de la mezcla', $encabezado_texto);
        $table->addCell(2800, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '1A5276'))->addTextRun($centrado)->addText('Puntos de muestreos', $textototal); // combina columna
        $table->addCell(1200, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Total puntos', $encabezado_texto);
        // $table->addCell(2500, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Tipo de instalación', $encabezado_texto);
        $table->addRow(); //fila
        $table->addCell(3450, $continua_fila);
        $table->addCell(1400, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell(1400, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);
        $table->addCell(1200, $continua_fila);
        $table->addCell(2500, $continua_fila);

        if (!empty($sql)) {
            // registros tabla
            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell(3450, $celda)->addTextRun($centrado)->addText($value->PRODUCTO_COMPONENTE, $texto);
                $table->addCell(1400, $celda)->addTextRun($centrado)->addText($value->SUMA_MUESTREO_PPT, $texto);
                $table->addCell(1400, $celda)->addTextRun($centrado)->addText($value->SUMA_MUESTREO_CT, $texto);
                $table->addCell(1200, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREO, $textonegrita);
                // $table->addCell(2500, $celda)->addTextRun($centrado)->addText($value->tipoinstalacion, $texto);
            }

            $plantillaword->setComplexBlock('tabla_resumen_quimicos1', $table);
        } else {

            $plantillaword->setValue('tabla_resumen_quimicos1', 'Se cuenta con ' . $numeros[0]->total_catsustancias . ' productos con una proporción  de componentes que incluyen diferentes sustancias químicas. Según el análisis realizado, no es necesario muestrear las sustancias químicas presentes en los productos de la empresa, debido a que las prioridades de muestreo obtenidas son bajas o muy bajas, por lo que no existe riesgo de exposición a sustancias químicas para los trabajadores.');
        }





        // TABLA 14 SUSTANCIAS QUIMICAS ADICIONALES A MUESTREAR POR SOLICITUD DEL CLIENTE
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select(' SELECT area.recsensorialarea_nombre AREA,
                                    IF(cliente.CATEGORIA_ID = 0 ,"Ambiente laboral", categoria.recsensorialcategoria_nombrecategoria) AS CATEGORIA,
                                    sus.SUSTANCIA_QUIMICA SUSTANCIA,
                                    IFNULL(cliente.PPT,0) PPT,
                                    IFNULL(cliente.CT,0) CT,
                                    cliente.PUNTOS
                            FROM recsensorial_tablaClientes_informes cliente
                            LEFT JOIN recsensorialarea area ON area.id = cliente.AREA_ID
                            LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = cliente.SUSTANCIA_ID
                            LEFT JOIN recsensorialcategoria categoria ON cliente.CATEGORIA_ID = categoria.id
                            WHERE cliente.RECONOCIMIENTO_ID = ?
                            ORDER BY AREA, CATEGORIA', [$recsensorial_id]);


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Áreas evaluadas', $encabezado_texto);
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell(1700, $encabezado_celda)->addTextRun($centrado)->addText('Componente', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);
        $table->addCell(1200, $encabezado_celda)->addTextRun($centrado)->addText('PUNTOS', $encabezado_texto);


        // registros tabla
        $area = 'xxx';
        $categoria = 'xxx';
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->AREA) {
                // Si cambia de area, reinicia la categoria y sustancia
                $categoria = 'xxx';
                $sustancia = 'xxx';

                $table->addRow(); //fila
                $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->AREA, $texto);

                if ($categoria != $value->CATEGORIA) {
                    $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->CATEGORIA, $textonegrita);

                    if ($sustancia != $value->SUSTANCIA) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);


                        $sustancia = $value->SUSTANCIA;
                    } else {
                        $table->addCell(2300, $continua_fila);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);
                    }

                    $categoria = $value->CATEGORIA;
                } else {
                    $table->addCell(2300, $continua_fila);

                    if ($sustancia != $value->SUSTANCIA) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);

                        $sustancia = $value->SUSTANCIA;
                    } else {
                        $table->addCell(2300, $continua_fila);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);
                    }
                }

                $area = $value->AREA;
            } else {
                $table->addRow(); //fila
                $table->addCell(2300, $continua_fila);

                if ($categoria != $value->CATEGORIA) {
                    $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->CATEGORIA, $textonegrita);

                    if ($sustancia != $value->SUSTANCIA) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);

                        $sustancia = $value->SUSTANCIA;
                    } else {
                        $table->addCell(2300, $continua_fila);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);
                    }

                    $categoria = $value->CATEGORIA;
                } else {
                    $table->addCell(2300, $continua_fila);

                    if ($sustancia != $value->SUSTANCIA) {
                        $table->addCell(2300, $combinar_fila)->addTextRun($centrado)->addText($value->SUSTANCIA, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);

                        $sustancia = $value->SUSTANCIA;
                    } else {
                        $table->addCell(2300, $continua_fila);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PPT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->CT, $texto);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->PUNTOS, $texto);
                    }
                }
            }
        }


        if ($recsensorial[0]->PETICION_CLIENTE == 1) {

            $plantillaword->setComplexBlock('table_sustancias_cliente', $table);
            $plantillaword->setValue('titulo_tabla_cliente', 'Sustancias químicas adicionales para muestrear por solicitud del cliente.');
        } else {

            $plantillaword->setValue('table_sustancias_cliente', '');
            $plantillaword->setValue('titulo_tabla_cliente', '');
        }




        //================================================================================

        /*

        // New Word Document
        $PHPWord = new PHPWord();
        // New portrait section
        $section = $PHPWord->createSection();
        // Add header
        $header = $section->createHeader();
        $table = $header->addTable();
        $table->addRow();
        $table->addCell(4500)->addText('Ejemplo gabriel word.');
        // $table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));
        // Add footer
        $footer = $section->createFooter();
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));
        // Write some text
        $section->addTextBreak();
        $section->addText('Some text...');

        $section = $PHPWord->createSection();
        $section->addText('GABRIEL JIMENEZ DE LA CRUZ');

        $section = $PHPWord->createSection();
        $section->addText('${instalación}');
        $section->setValue('instalación', 'NOMBRE DE LA INSTALACIÓN');

        // Save File
        // $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        // $objWriter->save('HeaderFooter.docx');
        $PHPWord->save(storage_path('app\reportes\recsensorial\recsensorialquimicos_reporteword.docx'));

        */

        //CONCLUSIONES
        if ($recursos[0]->REQUIERE_CONCLUSION == 1) {

            $plantillaword->setValue('NUMERO', "14 ");
            $plantillaword->setValue('titulo_conclusiones', "Conclusiones");
            $plantillaword->setValue('conclusiones', $recursos[0]->CONCLUSION);
        } else {

            $plantillaword->setValue('NUMERO', "");
            $plantillaword->setValue('titulo_conclusiones', '');
            $plantillaword->setValue('conclusiones', '');
        }

        //================ ELABORACION DEL INFORME DE RECONOCIMIENTO DE QUIMICOS =================================================================

        //APOYO TECNIO
        $plantillaword->setValue('nombreApoyo_Cargo', $recsensorial[0]->recsensorial_repquimicos1nombre . "<w:br/>" . $recsensorial[0]->recsensorial_repquimicos1cargo);
        if ($recsensorial[0]->recsensorial_repquimicos1doc) {
            if (file_exists(storage_path('app/' . $recsensorial[0]->recsensorial_repquimicos1doc))) {

                $plantillaword->setImageValue('foto_apoyo', array('path' => storage_path('app/' . $recsensorial[0]->recsensorial_repquimicos1doc), 'width' => 300, 'height' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {

                $plantillaword->setValue('foto_apoyo', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('foto_apoyo', 'SIN IMAGEN');
        }



        // //LIDER DEL PROYECTO
        $plantillaword->setValue('nombreLider_Cargo', $recsensorial[0]->recsensorial_repquimicos2nombre . "<w:br/>" . $recsensorial[0]->recsensorial_repquimicos2cargo);
        if ($recsensorial[0]->recsensorial_repquimicos2doc) {
            if (file_exists(storage_path('app/' . $recsensorial[0]->recsensorial_repquimicos2doc))) {

                $plantillaword->setImageValue('foto_lider', array('path' => storage_path('app/' . $recsensorial[0]->recsensorial_repquimicos2doc), 'width' => 300, 'height' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {

                $plantillaword->setValue('foto_lider', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('foto_lider', 'SIN IMAGEN');
        }



        try {
            Storage::makeDirectory('reportes/recsensorial'); //crear directorio
            $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Informe - Reconocimiento de Químicos - ' . $recsensorial[0]->recsensorial_instalacion . '.docx')); //crear archivo word
            // $plantillaword->saveAs(public_path('app/reportes/recsensorial/reporte1_recsensorialword.docx'));

            // respuesta
            // $dato["msj"] = 'Informacion consultada correctamente';
            // return response()->json($dato);



            //Marcamos en la Base de datos que el reconocimiento ha sido descargado para que pueda ser validad
            $rec = recsensorialModel::findOrFail($recsensorial_id);
            $rec->recsensorial_quimicoFinalizado = 1;
            $rec->save();


            return response()->download(storage_path('app/reportes/recsensorial/Informe - Reconocimiento de Químicos - ' . $recsensorial[0]->recsensorial_instalacion . '.docx'))->deleteFileAfterSend(true);
        } catch (Exception $e) {
            $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosreporte1wordcliente($recsensorial_id)
    {
        //ZONA HORARIA EN ESPAÑOL
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_ALL, "es_ES");
        // setlocale(LC_TIME, "spanish");
        // dd(strftime("%d de %B(%m) de %Y", strtotime('2022-09-11')));



        // OBTENER DATOS GENERALES
        //================================================================================

        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $cliente = clienteModel::findOrFail($recsensorial->cliente_id);

        // LEER PLANTILLA WORD
        //================================================================================


        // $plantillaword = new TemplateProcessor(public_path('/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_fisicoscliente.docx'));    //Ruta carpeta public
        $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_quimicoscliente.docx')); //Ruta carpeta storage


        // PORTADA
        //================================================================================


        // LOGOS
        //-----------------------------------------



        if ($cliente->cliente_plantillalogoizquierdo) {
            if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoizquierdo))) {
                $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoizquierdo), 'height' => 39, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
        }


        if ($cliente->cliente_plantillalogoderecho) {
            if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoderecho))) {
                $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoderecho), 'height' => 39, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
        }


        if ($recsensorial->recsensorial_fotoinstalacion) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_fotoinstalacion))) {
                $plantillaword->setImageValue('INSTALACION_FOTO', array('path' => storage_path('app/' . $recsensorial->recsensorial_fotoinstalacion), 'height' => 280, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('INSTALACION_FOTO', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('INSTALACION_FOTO', 'SIN IMAGEN');
        }


        // DATOS
        //-----------------------------------------

        $meses_M = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        // $plantillaword->setValue('FECHA_CREACION', $meses_M[(date_format(date_create($recsensorial->recsensorial_fechainicio), 'm')-1)].' del '.date_format(date_create($recsensorial->recsensorial_fechainicio), 'Y'));
        setlocale(LC_ALL, "es_MX");
        $plantillaword->setValue('FECHA_CREACION', ucfirst(strftime("%B %Y", strtotime(date("d-m-Y", strtotime($recsensorial->recsensorial_fechainicio)))))); //ucfirst = primera letra mayuscula

        $plantillaword->setValue('INSTALACION_NOMBRE', $recsensorial->recsensorial_instalacion);

        $plantillaword->setValue('EMPRESA_RESPONSABLE', $cliente->cliente_plantillaempresaresponsable);

        $plantillaword->setValue('NUMERO_CONTRATO', $cliente->cliente_numerocontrato);

        $plantillaword->setValue('CONTRATO_DESCRIPCION', $cliente->cliente_descripcioncontrato);


        $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
            ->where('clientepartidas_tipo', 1) // reconocimiento
            ->where('catprueba_id', 2) // quimicos
            ->orderBy('updated_at', 'DESC')
            ->get();


        if (count($titulo_partida) > 0) {
            $plantillaword->setValue('TITULO_INFORME', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripción));
        } else {
            $plantillaword->setValue('TITULO_INFORME', 'TITULO DEL INFORME<w:br/>(INFORMACIÓN NO CARGADA EN EL SOFTWARE)');
        }


        if ($recsensorial->recsensorial_ordenservicio == "N/A" || $recsensorial->recsensorial_ordenservicio == "n/a"  || $recsensorial->recsensorial_ordenservicio == "N/a") {
            $plantillaword->setValue('ORDEN_SERVICIO', '');
        } else {
            $plantillaword->setValue('ORDEN_SERVICIO', 'Orden de servicio: ' . $recsensorial->recsensorial_ordenservicio);
        }


        $plantillaword->setValue('PIE_PAGINA', str_replace("\r\n", "<w:br/>", str_replace("\n\n", "<w:br/>", $cliente->cliente_plantillapiepagina . '<w:br/>' . 'FOLIO: ' . $recsensorial->recsensorial_folioquimico)));


        // CONTENIDO DATOS
        //================================================================================

        $meses_m = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

        $plantillaword->setValue('CLIENTE_NOMBRE_COMERCIAL', $cliente->cliente_NombreComercial);

        if ($recsensorial->recsensorial_fechainicio == $recsensorial->recsensorial_fechafin) {
            $plantillaword->setValue('RECONOCIMIENTO_FECHA', 'El día ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'd') . ' del ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'Y'));
        } else {
            if (date_format(date_create($recsensorial->recsensorial_fechainicio), 'm') == date_format(date_create($recsensorial->recsensorial_fechafin), 'm')) {
                $plantillaword->setValue('RECONOCIMIENTO_FECHA', 'los días ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'd') . ' y ' . date_format(date_create($recsensorial->recsensorial_fechafin), 'd') . ' de ' . $meses_m[(date_format(date_create($recsensorial->recsensorial_fechainicio), 'm') - 1)] . ' del ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'Y'));
            } else {
                $plantillaword->setValue('RECONOCIMIENTO_FECHA', 'los días ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'd') . ' de ' . $meses_m[(date_format(date_create($recsensorial->recsensorial_fechainicio), 'm') - 1)] . ' del ' . date_format(date_create($recsensorial->recsensorial_fechainicio), 'Y') . ' al ' . date_format(date_create($recsensorial->recsensorial_fechafin), 'd') . ' de ' . $meses_m[(date_format(date_create($recsensorial->recsensorial_fechafin), 'm') - 1)] . ' del ' . date_format(date_create($recsensorial->recsensorial_fechafin), 'Y'));
            }
        }


        // DATOS DEL CENTRO DE TRABAJO
        //================================================================================


        $plantillaword->setValue('CLIENTE_NOMBRE_COMERCIAL', $cliente->cliente_NombreComercial);
        $plantillaword->setValue('CLIENTE_RFC', $cliente->cliente_Rfc);
        // $plantillaword->setValue('INSTALACION_NOMBRE', $recsensorial->recsensorial_instalacion);
        $plantillaword->setValue('ACTIVIDAD_PRINCIPAL', $recsensorial->recsensorial_actividadprincipal);
        $plantillaword->setValue('DIRECCION', $recsensorial->recsensorial_direccion);
        $plantillaword->setValue('CODIGO_POSTAL', $recsensorial->recsensorial_codigopostal);
        $plantillaword->setValue('REPORESENTANTE_LEGAL', $recsensorial->recsensorial_representantelegal);
        $plantillaword->setValue('REPRESENTANTE_SEGURIDAD', $recsensorial->recsensorial_representanteseguridad);
        $plantillaword->setValue('INSTALACION_COORDENADAS', str_replace(", ", ".<w:br/>", $recsensorial->recsensorial_coordenadas));


        // FOTO UBICACION
        if ($recsensorial->recsensorial_fotoubicacion) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_fotoubicacion))) {
                $plantillaword->setImageValue('MAPA_UBICACION', array('path' => storage_path('app/' . $recsensorial->recsensorial_fotoubicacion), 'width' => 660, 'height' => 400, 'ratio' => true));
            } else {
                $plantillaword->setValue('MAPA_UBICACION', 'NO HAY IMAGEN QUE MOSTRAR.');
            }
        } else {
            $plantillaword->setValue('MAPA_UBICACION', 'NO HAY IMAGEN QUE MOSTRAR.');
        }


        // DESCRIPCION DEL PROCESO DE LA INSTALACIÓN
        // -------------------------------------------


        $parrafos = explode("\r\n", $recsensorial->recsensorial_descripcionproceso);
        $texto_nuevo = '';

        foreach ($parrafos as $key => $parrafo) {
            if ($key < (count($parrafos) - 1)) {
                $texto_nuevo .= '<w:p>
                                    <w:pPr>
                                        <w:jc w:val="both"/>
                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                    </w:pPr>
                                    <w:t>' . htmlspecialchars($parrafo) . '</w:t>
                                </w:p>'; //<w:br/>
            } else {
                $texto_nuevo .= '<w:t>' . htmlspecialchars($parrafo) . '</w:t>'; //La ultima linea, quitar BR (salto de linea)
            }
        }

        $plantillaword->setValue('INSTALACION_PROCESO', $texto_nuevo);


        // DISEÑO TABLAS
        //================================================================================

        // FORMATO
        $font_size = 11;
        $bgColor_encabezado = '#0C3F64'; //#1A5276
        $fuente = 'Montserrat';
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


        // TABLA INVENTARIO DE SUSTANCIAS
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialquimicosinventario.recsensorial_id,
                                -- recsensorialquimicosinventario.id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                IFNULL(catsustancia.catsustancia_nombre, "Sin dato") AS catsustancia_nombre,
                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                catestadofisicosustancia.catestadofisicosustancia_estado,
                                catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                catsustancia.catgradoriesgosalud_id,
                                catgradoriesgosalud.catgradoriesgosalud_clasificacion 
                            FROM
                                recsensorialquimicosinventario
                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id
                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id 
                            WHERE
                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . '
                            GROUP BY
                                recsensorialquimicosinventario.recsensorial_id,
                                -- recsensorialquimicosinventario.id,
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                catsustancia.id,
                                catsustancia.catsustancia_nombre,
                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                catestadofisicosustancia.catestadofisicosustancia_estado,
                                catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                catsustancia.catgradoriesgosalud_id,
                                catgradoriesgosalud.catgradoriesgosalud_clasificacion
                            ORDER BY
                                recsensorialarea.id ASC,
                                catsustancia.id ASC');

        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .15);
        $col_2 = ($width_table * .20);
        $col_3 = ($width_table * .15);
        $col_4 = ($width_table * .15);
        $col_5 = ($width_table * .20);
        $col_6 = ($width_table * .15);

        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Nombre de la<w:br />sustancia', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad manejada por jornada de trabajo (i, ii, iii)', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Estado físico de la<w:br />sustancia química', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Vía(s) de ingreso<w:br />al organismo', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Grado de riesgo<w:br />a la salud', $encabezado_texto);

        // FILAS
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialquimicosinventario_cantidad . " " . $value->catunidadmedidasustacia_abreviacion, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catgradoriesgosalud_id . "/" . $value->catgradoriesgosalud_clasificacion, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialquimicosinventario_cantidad . " " . $value->catunidadmedidasustacia_abreviacion, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catgradoriesgosalud_id . "/" . $value->catgradoriesgosalud_clasificacion, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_QUIMICOS_INVENTARIO', $table);


        // TABLA CARACTERISTICAS DE LAS SUSTANCIAS
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialquimicosinventario.recsensorial_id,
                                -- recsensorialquimicosinventario.id,
                                -- IFNULL( recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                IFNULL( catsustancia.catsustancia_nombre, "Sin dato" ) AS catsustancia_nombre,
                                catvolatilidad.catvolatilidad_tipo,
                                REPLACE(catsustanciacomponente.catsustanciacomponente_nombre, "ˏ", ",") AS catsustanciacomponente_nombre,
                                catsustanciacomponente.catsustanciacomponente_cas,
                                catsustanciacomponente.catsustanciacomponente_ebullicion,
                                catsustanciacomponente.catsustanciacomponente_porcentaje,
                                catsustanciacomponente.catsustanciacomponente_pesomolecular,
                                IF(catsustanciacomponente.catsustanciacomponente_estadofisico = "ND", "ND", catestadofisicosustancia.catestadofisicosustancia_estado) AS catestadofisicosustancia_estado,
                                catsustanciacomponente.catsustanciacomponente_volatilidad 
                            FROM
                                recsensorialquimicosinventario
                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                INNER JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = catsustancia.id
                                LEFT JOIN catestadofisicosustancia ON catsustanciacomponente.catsustanciacomponente_estadofisico = catestadofisicosustancia.id 
                            WHERE
                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                            GROUP BY
                                recsensorialquimicosinventario.recsensorial_id,
                                -- recsensorialquimicosinventario.id,
                                -- recsensorialarea.recsensorialarea_nombre,
                                catsustancia.id,
                                catsustancia.catsustancia_nombre,
                                catvolatilidad.catvolatilidad_tipo,
                                catsustanciacomponente.catsustanciacomponente_nombre,
                                catsustanciacomponente.catsustanciacomponente_cas,
                                catsustanciacomponente.catsustanciacomponente_ebullicion,
                                catsustanciacomponente.catsustanciacomponente_porcentaje,
                                catsustanciacomponente.catsustanciacomponente_pesomolecular,
                                catsustanciacomponente.catsustanciacomponente_estadofisico,
                                catestadofisicosustancia.catestadofisicosustancia_estado,
                                catsustanciacomponente.catsustanciacomponente_volatilidad
                            ORDER BY
                                -- recsensorialarea.recsensorialarea_nombre ASC,
                                catsustancia.id ASC,
                                catsustanciacomponente.catsustanciacomponente_nombre ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .20);
        $col_2 = ($width_table * .20);
        $col_3 = ($width_table * .10);
        $col_4 = ($width_table * .10);
        $col_5 = ($width_table * .10);
        $col_6 = ($width_table * .10);
        $col_7 = ($width_table * .10);
        $col_8 = ($width_table * .10);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Nombre de la<w:br />sustancia', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Componentes<w:br />de la mezcla', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Número CAS', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Temperatura de ebullición (°C)', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de componente (%)', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Peso Molecular (gr/mol)', $encabezado_texto);
        $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Estado físico', $encabezado_texto);
        $table->addCell($col_8, $encabezado_celda)->addTextRun($centrado)->addText('Volatilidad', $encabezado_texto);

        // FILAS
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila

            if ($sustancia != $value->catsustancia_nombre) {
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_cas, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_ebullicion, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_porcentaje, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_pesomolecular, $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_volatilidad, $texto);

                $sustancia = $value->catsustancia_nombre;
            } else {
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_cas, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_ebullicion, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_porcentaje, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_pesomolecular, $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->catestadofisicosustancia_estado, $texto);
                $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_volatilidad, $texto);
            }
        }

        $plantillaword->setComplexBlock('TABLA_QUIMICOS_CARACTERISTICAS', $table);



        // TABLA GRADO DE RIESGO Y PELIGRO A LA SALUD
        //================================================================================



        $sql = DB::select('SELECT
                                recsensorialquimicosinventario.recsensorial_id,
                                -- IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                -- recsensorialquimicosinventario.id,
                                recsensorialquimicosinventario.catsustancia_id,
                                IFNULL( catsustancia.catsustancia_nombre, "Sin dato" ) AS catsustancia_nombre,
                                REPLACE(catsustanciacomponente.catsustanciacomponente_nombre, "ˏ", ",") AS catsustanciacomponente_nombre,
                                IFNULL( catsustanciacomponente.catsustanciacomponente_exposicionppt, 0 ) AS catsustanciacomponente_exposicionppt,
                                IFNULL( catsustanciacomponente.catsustanciacomponente_exposicionctop, 0 ) AS catsustanciacomponente_exposicionctop,
                                catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                CONCAT( "(", catgradoriesgosalud.id, ")<w:br />", catgradoriesgosalud.catgradoriesgosalud_clasificacion ) AS catgradoriesgosalud_clasificacion 
                            FROM
                                recsensorialquimicosinventario
                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                LEFT JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = catsustancia.id
                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                            WHERE
                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                            GROUP BY
                                recsensorialquimicosinventario.recsensorial_id,
                                -- recsensorialarea.recsensorialarea_nombre,
                                -- recsensorialquimicosinventario.id,
                                recsensorialquimicosinventario.catsustancia_id,
                                catsustancia.id,
                                catsustancia.catsustancia_nombre,
                                catsustanciacomponente.catsustanciacomponente_nombre,
                                catsustanciacomponente.catsustanciacomponente_exposicionppt,
                                catsustanciacomponente.catsustanciacomponente_exposicionctop,
                                catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                catgradoriesgosalud.id,
                                catgradoriesgosalud.catgradoriesgosalud_clasificacion
                            ORDER BY
                                catsustancia.id ASC,
                                catsustanciacomponente.catsustanciacomponente_nombre ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .20);
        $col_2 = ($width_table * .20);
        $col_3 = ($width_table * .18);
        $col_4 = ($width_table * .18);
        $col_5 = ($width_table * .12);
        $col_6 = ($width_table * .12);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Nombre de la sustancia', $encabezado_texto);
        $table->addCell($col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Componentes<w:br />de la mezcla', $encabezado_texto);
        $table->addCell($col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Vía de ingreso<w:br />al organismo', $encabezado_texto);
        $table->addCell($col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Grado de riesgo<w:br />a la salud', $encabezado_texto);
        $table->addCell(($col_5 + $col_6), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Valores límite<w:br />de exposición', $encabezado_texto); // combina columna
        $table->addRow(); //fila
        $table->addCell($col_1, $continua_fila);
        $table->addCell($col_2, $continua_fila);
        $table->addCell($col_3, $continua_fila);
        $table->addCell($col_4, $continua_fila);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);


        // FILAS
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            if ($sustancia != $value->catsustancia_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->catsustancia_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_nombre, $texto);
                $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                $table->addCell($col_4, $combinar_fila)->addTextRun($centrado)->addText($value->catgradoriesgosalud_clasificacion, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_exposicionppt, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_exposicionctop, $texto);

                $sustancia = $value->catsustancia_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_nombre, $texto);
                $table->addCell($col_3, $continua_fila)->addTextRun($centrado)->addText($value->catviaingresoorganismo_viaingreso, $texto);
                $table->addCell($col_4, $continua_fila)->addTextRun($centrado)->addText($value->catgradoriesgosalud_clasificacion, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_exposicionppt, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->catsustanciacomponente_exposicionctop, $texto);
            }
        }

        $plantillaword->setComplexBlock('TABLA_GRADO_RIESGO', $table);



        // TABLA FUENTES GENERADORAS
        //================================================================================



        $sql = DB::select('SELECT
                                recsensorialmaquinaria.recsensorial_id,
                                recsensorialmaquinaria.recsensorialarea_id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                recsensorialmaquinaria.id,
                                IFNULL(recsensorialmaquinaria.recsensorialmaquinaria_nombre, "Sin dato") AS recsensorialmaquinaria_nombre,
                                recsensorialmaquinaria.recsensorialmaquinaria_cantidad,
                                recsensorialmaquinaria.recsensorialmaquinaria_afecta 
                            FROM
                                recsensorialmaquinaria
                                LEFT JOIN recsensorialarea ON recsensorialmaquinaria.recsensorialarea_id = recsensorialarea.id 
                            WHERE
                                recsensorialmaquinaria.recsensorial_id = 182 AND (recsensorialmaquinaria.recsensorialmaquinaria_afecta = 2 ||  recsensorialmaquinaria.recsensorialmaquinaria_afecta = 3) 
                            ORDER BY
                                recsensorialarea.id ASC,
                                recsensorialmaquinaria.recsensorialmaquinaria_nombre ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .30);
        $col_2 = ($width_table * .30);
        $col_3 = ($width_table * .30);
        $col_4 = ($width_table * .10);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Agentes químicos identificados', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Fuente generadora', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);

        // FILAS
        $area = 'xxx';
        foreach ($sql as $key => $value) {

            $agentes = DB::select('SELECT
                                        IFNULL(CONCAT("● ", REPLACE(GROUP_CONCAT(TABLA.catsustancia_nombre), ",", "<w:br />● ")), "Sin dato") AS agentes_quimicos
                                    FROM
                                        (
                                            SELECT  
                                                catsustancia.catsustancia_nombre
                                            FROM
                                                recsensorialquimicosinventario
                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id 
                                            WHERE
                                                recsensorialquimicosinventario.recsensorialarea_id = ' . $value->recsensorialarea_id . '
                                            GROUP BY
                                                catsustancia.catsustancia_nombre
                                        ) AS TABLA');


            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell($col_2, $combinar_fila)->addTextRun($izquierda)->addText($agentes[0]->agentes_quimicos, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $continua_fila);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);
            }
        }

        $plantillaword->setComplexBlock('TABLA_FUENTES_GENERADORAS', $table);



        // TABLA CONDICIONES Y CARACTERISTICAS DEL AREA DE PROCESO
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialarea.recsensorial_id,
                                recsensorialarea.id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                recsensorialarea.recsensorialarea_condicion,
                                recsensorialarea.recsensorialarea_caracteristica,
                                recsensorialarea.recsensorialarea_extraccionaire,
                                recsensorialarea.recsensorialarea_inyeccionaire,
                                recsensorialarea.recsensorialarea_generacioncontaminante 
                            FROM
                                recsensorialarea 
                            WHERE
                                recsensorialarea.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialarea.id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .15);
        $col_2 = ($width_table * .15);
        $col_3 = ($width_table * .15);
        $col_4 = ($width_table * .15);
        $col_5 = ($width_table * .15);
        $col_6 = ($width_table * .25);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Condición del lugar<w:br />(Abierto/Cerrado)', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de extracción de aire<w:br />(general/localizado)', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Sistema de inyección de aire<w:br />(general/localizado)', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Características del proceso<w:br />(Continuo/Intermitente)', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Generación del contaminante', $encabezado_texto);

        // FILAS
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
            $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_condicion, $texto);
            $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_extraccionaire, $texto);
            $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_inyeccionaire, $texto);
            $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_caracteristica, $texto);
            $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_generacioncontaminante, $texto);
        }

        $plantillaword->setComplexBlock('TABLA_CONDICIONES_CARACTERISTICAS', $table);



        // TABLA - PONDERACION-1 - PRIORIDAD DE MUESTREO DE LAS SUSTANCIAS QUIMICAS
        //================================================================================


        $sql = DB::select('SELECT
                                TABLA2.area_id,
                                TABLA2.area_nombre,
                                -- TABLA2.categoria_nombre,
                                TABLA2.sustancia_nombre,
                                TABLA2.ponderacion_cantidad,
                                TABLA2.ponderacion_riesgo,
                                TABLA2.ponderacion_volatilidad,
                                TABLA2.TOTAL,
                                TABLA2.PRIORIDAD,
                                TABLA2.COLOR
                            FROM
                                (
                                    SELECT
                                        area_id,
                                        IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                        IFNULL(CONCAT(departamentoNombre, " (", categoria_nombre, ")"), "Sin dato") AS categoria_nombre,
                                        IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                        IF(cancerigeno = 0, ponderacion_cantidad, 5) AS ponderacion_cantidad,
                                        ponderacion_riesgo,
                                        ponderacion_volatilidad,
                                        (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                        (
                                            CASE
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                ELSE "Muy baja"
                                            END
                                        ) AS PRIORIDAD,
                                        (
                                            CASE
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "#E74C3C"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "#F1C40F"
                                                ELSE "#2ECC71"
                                            END
                                        ) AS COLOR
                                    FROM
                                        (
                                            SELECT
                                                recsensorialquimicosinventario.recsensorial_id,
                                                recsensorialquimicosinventario.id,
                                                recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                catdepartamento.catdepartamento_nombre AS departamentoNombre,
                                                recsensorialcategoria.sumaHorasJornada AS horas_jornada,
                                                recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                IFNULL((
                                                    SELECT
                                                        COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                    FROM
                                                        catsustanciacomponente 
                                                    WHERE
                                                        catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                        AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                ), 0) AS cancerigeno,
                                                (
                                                    CASE
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        ELSE 0
                                                    END
                                                ) AS ponderacion_cantidad,
                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                (
                                                    CASE
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                        ELSE 1
                                                    END
                                                ) AS tot_personalexposicion,
                                                (
                                                    CASE
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                        ELSE 1
                                                    END
                                                ) AS tot_tiempoexposicion,
                                                (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                -- ,catsustancia.catestadofisicosustancia_id,
                                                -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                -- catsustancia.catvolatilidad_id,
                                                -- catvolatilidad.catvolatilidad_tipo,
                                                -- catsustancia.catviaingresoorganismo_id,
                                                -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                -- catsustancia.catcategoriapeligrosalud_id,
                                                -- catcategoriapeligrosalud.id,
                                                -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                -- catsustancia.catgradoriesgosalud_id,
                                                -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                            FROM
                                                recsensorialquimicosinventario
                                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                                LEFT JOIN catdepartamento ON catdepartamento.id = recsensorialcategoria.catdepartamento_id
                                            WHERE
                                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                            GROUP BY
                                                recsensorialquimicosinventario.recsensorial_id,
                                                recsensorialquimicosinventario.id,
                                                recsensorialquimicosinventario.recsensorialarea_id,
                                                recsensorialarea.recsensorialarea_nombre,
                                                recsensorialquimicosinventario.recsensorialcategoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                recsensorialareacategorias.recsensorialareacategorias_total,
                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                recsensorialquimicosinventario.catsustancia_id,
                                                catsustancia.catsustancia_nombre,
                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                catvolatilidad.catvolatilidad_ponderacion,
                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                            -- ORDER BY
                                                -- area_nombre ASC,
                                                -- categoria_nombre ASC,
                                                -- sustancia_nombre ASC
                                        ) AS TABLA1
                                    -- ORDER BY
                                        -- area_nombre ASC,
                                        -- categoria_nombre ASC,
                                        -- TOTAL DESC
                                ) AS TABLA2
                            GROUP BY
                                TABLA2.area_id,
                                TABLA2.area_nombre,
                                -- TABLA2.categoria_nombre,
                                TABLA2.sustancia_nombre,
                                TABLA2.ponderacion_cantidad,
                                TABLA2.ponderacion_riesgo,
                                TABLA2.ponderacion_volatilidad,
                                TABLA2.TOTAL,
                                TABLA2.PRIORIDAD,
                                TABLA2.COLOR
                            ORDER BY
                                -- TABLA2.area_nombre ASC,
                                -- TABLA2.categoria_nombre ASC,
                                -- TABLA2.sustancia_nombre ASC
                                TABLA2.area_id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .20);
        $col_2 = ($width_table * .20);
        $col_3 = ($width_table * .12);
        $col_4 = ($width_table * .12);
        $col_5 = ($width_table * .12);
        $col_6 = ($width_table * .12);
        $col_7 = ($width_table * .12);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell($col_2, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Sustancia química', $encabezado_texto);
        $table->addCell(($col_3 + $col_4 + $col_5), array('gridSpan' => 3, 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Valor de ponderación', $encabezado_texto); // combina columna
        $table->addCell($col_6, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('TOTAL<w:br />(Suma de los valores de ponderación)', $encabezado_texto);
        $table->addCell($col_7, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Prioridad de muestreo<w:br />(Tabla 11)', $encabezado_texto);

        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $continua_fila);
        $table->addCell($col_2, $continua_fila);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad manejada<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Clasificación de riesgo<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Volatilidad<w:br />(Tabla 10)', $encabezado_texto);
        $table->addCell($col_6, $continua_fila);
        $table->addCell($col_7, $continua_fila);

        // FILAS
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->area_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->area_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->ponderacion_cantidad, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->ponderacion_riesgo, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->ponderacion_volatilidad, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->TOTAL, $texto);
                $table->addCell($col_7, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);

                $area = $value->area_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->ponderacion_cantidad, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->ponderacion_riesgo, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->ponderacion_volatilidad, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->TOTAL, $texto);
                $table->addCell($col_7, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD, $texto);
            }
        }

        $plantillaword->setComplexBlock('TABLA_PRIORIDAD_MUESTREO', $table);



        // TABLA - PONDERACION 2 - DETERMINACION DE LOS GRUPOS DE EXPOSICION HOMOGENEA
        //================================================================================


        $sql = DB::select('SELECT
                                -- *,
                                TABLA2.area_id,
                                IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                TABLA2.categoria_id,
                                -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                IFNULL(categoria_nombre, "Sin dato") AS categoria_nombre,
                                TABLA2.id,
                                IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                tot_ingresoorganismo,
                                tot_personalexposicion,
                                tot_tiempoexposicion,
                                (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                (
                                    CASE
                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                        ELSE "Baja"
                                    END
                                ) AS PRIORIDAD2,
                                (
                                    CASE
                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "#E74C3C"
                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "#F1C40F"
                                        ELSE "#2ECC71"
                                    END
                                ) AS COLOR
                            FROM
                                (
                                    SELECT
                                        *,
                                        (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                        (
                                            CASE
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                ELSE "Muy baja"
                                            END
                                        ) AS PRIORIDAD
                                    FROM
                                        (
                                            SELECT
                                                recsensorialquimicosinventario.recsensorial_id,
                                                recsensorialquimicosinventario.id,
                                                recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                recsensorialcategoria.sumaHorasJornada AS horas_jornada,
                                                recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                IFNULL((
                                                    SELECT
                                                        COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                    FROM
                                                        catsustanciacomponente 
                                                    WHERE
                                                        catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                        AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                ), 0) AS cancerigeno,
                                                (
                                                    CASE
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                        ELSE 0
                                                    END
                                                ) AS ponderacion_cantidad,
                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                (
                                                    CASE
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                        ELSE 1
                                                    END
                                                ) AS tot_personalexposicion,
                                                (
                                                    CASE
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                        ELSE 1
                                                    END
                                                ) AS tot_tiempoexposicion,
                                                (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                -- ,catsustancia.catestadofisicosustancia_id,
                                                -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                -- catsustancia.catvolatilidad_id,
                                                -- catvolatilidad.catvolatilidad_tipo,
                                                -- catsustancia.catviaingresoorganismo_id,
                                                -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                -- catsustancia.catcategoriapeligrosalud_id,
                                                -- catcategoriapeligrosalud.id,
                                                -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                -- catsustancia.catgradoriesgosalud_id,
                                                -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                            FROM
                                                recsensorialquimicosinventario
                                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                            WHERE
                                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                            GROUP BY
                                                recsensorialquimicosinventario.recsensorial_id,
                                                recsensorialquimicosinventario.id,
                                                recsensorialquimicosinventario.recsensorialarea_id,
                                                recsensorialarea.recsensorialarea_nombre,
                                                recsensorialquimicosinventario.recsensorialcategoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                recsensorialareacategorias.recsensorialareacategorias_total,
                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                recsensorialquimicosinventario.catsustancia_id,
                                                catsustancia.catsustancia_nombre,
                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                catvolatilidad.catvolatilidad_ponderacion,
                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                            -- ORDER BY
                                                -- area_nombre ASC,
                                                -- categoria_nombre ASC,
                                                -- sustancia_nombre ASC
                                        ) AS TABLA1
                                    ORDER BY
                                        area_id ASC,
                                        categoria_id ASC,
                                        TOTAL DESC
                                ) AS TABLA2
                            WHERE
                                TOTAL >= 8
                            ORDER BY
                                TABLA2.area_id ASC,
                                TABLA2.categoria_id ASC,
                                TABLA2.id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .14);
        $col_2 = ($width_table * .14);
        $col_3 = ($width_table * .14);
        $col_4 = ($width_table * .12);
        $col_5 = ($width_table * .12);
        $col_6 = ($width_table * .12);
        $col_7 = ($width_table * .12);
        $col_8 = ($width_table * .12);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Área/zona', $encabezado_texto);
        $table->addCell($col_2, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell($col_3, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Sustancias químicas presentes en el área, proceso o puesto de trabajo<w:br />(Tabla 9)', $encabezado_texto);
        $table->addCell(($col_4 + $col_5 + $col_6), array('gridSpan' => 3, 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Valor de ponderación', $encabezado_texto); // combina columna
        $table->addCell($col_7, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('TOTAL<w:br />(Suma de los valores de ponderación)', $encabezado_texto);
        $table->addCell($col_8, array('vMerge' => 'restart', 'bgColor' => $bgColor_encabezado, 'valign' => 'center'))->addTextRun($centrado)->addText('Prioridad de muestreo<w:br />(Tabla 14)', $encabezado_texto);

        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $continua_fila);
        $table->addCell($col_2, $continua_fila);
        $table->addCell($col_3, $continua_fila);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Vía(s) de ingreso<w:br />al organismo<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Número de<w:br />POE expuesto<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de<w:br />exposición<w:br />(Tabla 12)', $encabezado_texto);
        $table->addCell($col_7, $continua_fila);
        $table->addCell($col_8, $continua_fila);


        // FILAS
        $area = 'xxx';
        $categoria = 'xxx';
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->area_nombre) {
                // Si cambia de area, reinicia la categoria y sustancia
                $categoria = 'xxx';
                $sustancia = 'xxx';

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->area_nombre, $texto);

                if ($categoria != $value->categoria_nombre) {
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->categoria_nombre, $textonegrita);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                    }

                    $categoria = $value->categoria_nombre;
                } else {
                    $table->addCell($col_2, $continua_fila);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                    }
                }

                $area = $value->area_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);

                if ($categoria != $value->categoria_nombre) {
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->categoria_nombre, $textonegrita);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                    }

                    $categoria = $value->categoria_nombre;
                } else {
                    $table->addCell($col_2, $continua_fila);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_ingresoorganismo, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tot_personalexposicion, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->tot_tiempoexposicion, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->TOTAL2, $texto);
                        $table->addCell($col_8, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                    }
                }
            }
        }

        $plantillaword->setComplexBlock('TABLA_DETERMINACION_GRUPOS', $table);



        // TABLA - PONDERACION 3 - GRUPOS DE EXPOSICION HOMOGENEA
        //================================================================================


        $sql = DB::select('SELECT
                                *,
                                (
                                    CASE
                                        WHEN TOTAL2 >= 9 THEN "#E74C3C"
                                        ELSE "#F1C40F"
                                    END
                                ) AS COLOR
                            FROM
                                (
                                    SELECT
                                        -- *,
                                        TABLA2.area_id,
                                        IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                        TABLA2.categoria_id,
                                        -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                        IFNULL(categoria_nombre, "Sin dato") AS categoria_nombre,
                                        TABLA2.id,
                                        sustancia_id,
                                        IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                        tot_trabajadores,
                                        tiempo_expo,
                                        frecuencia_expo,
                                        suma_tiempoexposicion,
                                        horas_jornada,
                                        (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                        (
                                            CASE
                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                ELSE "Baja"
                                            END
                                        ) AS PRIORIDAD2,
                                        (
                                            CASE
                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                    CASE
                                                        WHEN tot_trabajadores > 100 THEN 20
                                                        WHEN tot_trabajadores >= 51 THEN 15
                                                        WHEN tot_trabajadores >= 26 THEN 8
                                                        WHEN tot_trabajadores >= 16 THEN 5
                                                        WHEN tot_trabajadores >= 9 THEN 3
                                                        WHEN tot_trabajadores >= 3 THEN 2
                                                        ELSE 1
                                                    END
                                                ELSE 
                                                    CASE
                                                        WHEN tot_trabajadores > 100 THEN 10
                                                        WHEN tot_trabajadores >= 51 THEN 7
                                                        WHEN tot_trabajadores >= 31 THEN 5
                                                        WHEN tot_trabajadores >= 21 THEN 4
                                                        WHEN tot_trabajadores >= 11 THEN 3
                                                        WHEN tot_trabajadores >= 6 THEN 2
                                                        ELSE 1
                                                    END
                                            END
                                        ) AS NUMERO_MUESTREOS
                                    FROM
                                        (
                                            SELECT
                                                *,
                                                (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                (
                                                    CASE
                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                        ELSE "Muy baja"
                                                    END
                                                ) AS PRIORIDAD
                                            FROM
                                                (
                                                    SELECT
                                                        recsensorialquimicosinventario.recsensorial_id,
                                                        recsensorialquimicosinventario.id,
                                                        recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                        recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                        recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                        ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                        recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                        recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                        recsensorialcategoria.sumaHorasJornada AS horas_jornada,
                                                        recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                        -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                        -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                        ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                        ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                        recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                        catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                        IFNULL((
                                                            SELECT
                                                                COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                            FROM
                                                                catsustanciacomponente 
                                                            WHERE
                                                                catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                        ), 0) AS cancerigeno,
                                                        (
                                                            CASE
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                ELSE 0
                                                            END
                                                        ) AS ponderacion_cantidad,
                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                        catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                        (
                                                            CASE
                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                ELSE 1
                                                            END
                                                        ) AS tot_personalexposicion,
                                                        (
                                                            CASE
                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                ELSE 1
                                                            END
                                                        ) AS tot_tiempoexposicion,
                                                        (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                        -- ,catsustancia.catestadofisicosustancia_id,
                                                        -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                        -- catsustancia.catvolatilidad_id,
                                                        -- catvolatilidad.catvolatilidad_tipo,
                                                        -- catsustancia.catviaingresoorganismo_id,
                                                        -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                        -- catsustancia.catcategoriapeligrosalud_id,
                                                        -- catcategoriapeligrosalud.id,
                                                        -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                        -- catsustancia.catgradoriesgosalud_id,
                                                        -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                    FROM
                                                        recsensorialquimicosinventario
                                                        LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                        LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                        AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                        LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                        LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                        LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                        LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                        LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                        LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                        LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                                    WHERE
                                                        recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                                    GROUP BY
                                                        recsensorialquimicosinventario.recsensorial_id,
                                                        recsensorialquimicosinventario.id,
                                                        recsensorialquimicosinventario.recsensorialarea_id,
                                                        recsensorialarea.recsensorialarea_nombre,
                                                        recsensorialquimicosinventario.recsensorialcategoria_id,
                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                        ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                        recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                                        ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                        recsensorialareacategorias.recsensorialareacategorias_total,
                                                        ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                        ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                        recsensorialquimicosinventario.catsustancia_id,
                                                        catsustancia.catsustancia_nombre,
                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                        recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                        recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                        catvolatilidad.catvolatilidad_ponderacion,
                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                    -- ORDER BY
                                                        -- area_nombre ASC,
                                                        -- categoria_nombre ASC,
                                                        -- sustancia_nombre ASC
                                                ) AS TABLA1
                                            ORDER BY
                                                area_id ASC,
                                                categoria_id ASC,
                                                TOTAL DESC
                                        ) AS TABLA2
                                    WHERE
                                        TOTAL >= 8
                                    ORDER BY
                                        TABLA2.area_id ASC,
                                        TABLA2.categoria_id ASC,
                                        TABLA2.id ASC
                                ) AS TABLA3
                            WHERE
                                TOTAL2 >= 4
                            ORDER BY
                                TABLA3.area_id ASC,
                                TABLA3.categoria_id ASC,
                                TABLA3.id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .10);
        $col_2 = ($width_table * .10);
        $col_3 = ($width_table * .10);
        $col_4 = ($width_table * .10);
        $col_5 = ($width_table * .10);
        $col_6 = ($width_table * .10);
        $col_7 = ($width_table * .10);
        $col_8 = ($width_table * .10);
        $col_9 = ($width_table * .10);
        $col_10 = ($width_table * .10);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Áreas evaluadas', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Agentes químicos evaluados', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de exposición (minutos)', $encabezado_texto);
        $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Frecuencia de exposición por jornada', $encabezado_texto);
        $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de exposición total (minutos)', $encabezado_texto);
        $table->addCell($col_8, $encabezado_celda)->addTextRun($centrado)->addText('Jornada de trabajo (horas)', $encabezado_texto);
        $table->addCell($col_9, $encabezado_celda)->addTextRun($centrado)->addText('Prioridad de muestreo', $encabezado_texto);
        $table->addCell($col_10, $encabezado_celda)->addTextRun($centrado)->addText('Número de POE a considerar', $encabezado_texto);

        // registros tabla
        $area = 'xxx';
        $categoria = 'xxx';
        $sustancia = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->area_nombre) {
                // Si cambia de area, reinicia la categoria y sustancia
                $categoria = 'xxx';
                $sustancia = 'xxx';

                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->area_nombre, $texto);

                if ($categoria != $value->categoria_nombre) {
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->categoria_nombre, $textonegrita);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);
                    }

                    $categoria = $value->categoria_nombre;
                } else {
                    $table->addCell($col_2, $continua_fila);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);
                    }
                }

                $area = $value->area_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);

                if ($categoria != $value->categoria_nombre) {
                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->categoria_nombre, $textonegrita);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);
                    }

                    $categoria = $value->categoria_nombre;
                } else {
                    $table->addCell($col_2, $continua_fila);

                    if ($sustancia != $value->sustancia_nombre) {
                        $table->addCell($col_3, $combinar_fila)->addTextRun($centrado)->addText($value->sustancia_nombre, $texto);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);

                        $sustancia = $value->sustancia_nombre;
                    } else {
                        $table->addCell($col_3, $continua_fila);
                        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->tot_trabajadores, $texto);
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tiempo_expo, $texto);
                        $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->frecuencia_expo, $texto);
                        $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->suma_tiempoexposicion, $texto);
                        $table->addCell($col_8, $celda)->addTextRun($centrado)->addText($value->horas_jornada, $texto);
                        $table->addCell($col_9, array('bgColor' => $value->COLOR, 'valign' => 'center'))->addTextRun($centrado)->addText($value->PRIORIDAD2, $texto);
                        $table->addCell($col_10, $celda)->addTextRun($centrado)->addText($value->NUMERO_MUESTREOS, $texto);
                    }
                }
            }
        }

        $plantillaword->setComplexBlock('TABLA_GRUPOS_EXPOSICION', $table);



        // TABLA ACTIVIDADES DEL PERSONAL EXPUESTO
        //================================================================================



        $sql = DB::select('SELECT
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                recsensorialcategoria.id,
                                -- IFNULL(CONCAT(recsensorialareacategorias.recsensorialareacategorias_geh, ".- ", recsensorialcategoria.recsensorialcategoria_nombrecategoria), "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                recsensorialareacategorias.recsensorialareacategorias_actividad 
                            FROM
                                recsensorialarea
                                INNER JOIN recsensorialareacategorias ON recsensorialarea.id = recsensorialareacategorias.recsensorialarea_id
                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                            WHERE
                                recsensorialarea.recsensorial_id = ' . $recsensorial_id . '
                            GROUP BY
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                recsensorialcategoria.id,
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                recsensorialareacategorias.recsensorialareacategorias_actividad
                            ORDER BY
                                recsensorialarea.id ASC,
                                recsensorialcategoria.id ASC');



        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .12);
        $col_2 = ($width_table * .44);
        $col_3 = ($width_table * .44);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Áreas evaluadas', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('GEH / categoría', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Actividad', $encabezado_texto);


        // FILA
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_actividad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_actividad, $texto);
            }
        }

        $plantillaword->setComplexBlock('TABLA_ACTIVIDADES_PERSONAL', $table);



        // TABLA EQUIPO DE PROTECCION PERSONAL
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialequipopp.recsensorial_id,
                                IFNULL(IF( recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", CONCAT( recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_nombrecategoria, ")" ) ), "Sin dato") AS categoria,
                                recsensorialequipopp.catpartecuerpo_id,
                                catpartecuerpo.catpartecuerpo_nombre,
                                des.CLAVE_EPP as  recsensorialequipopp_descripcion
                            FROM
                                recsensorialequipopp
                                LEFT JOIN recsensorialcategoria ON recsensorialequipopp.recsensorialcategoria_id = recsensorialcategoria.id
                                LEFT JOIN catpartecuerpo ON recsensorialequipopp.catpartecuerpo_id = catpartecuerpo.id 
                                LEFT JOIN catpartescuerpo_descripcion des ON des.ID_PARTESCUERPO_DESCRIPCION = recsensorialequipopp.catpartescuerpo_descripcion_id
                            WHERE
                                recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC,
                                recsensorialequipopp.catpartecuerpo_id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .30);
        $col_2 = ($width_table * .30);
        $col_3 = ($width_table * .40);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Parte del cuerpo', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Equipo de protección personal básico proporcionado', $encabezado_texto);


        // registros tabla
        $categoria = 'xxx';
        foreach ($sql as $key => $value) {
            if ($categoria != $value->categoria) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->categoria, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catpartecuerpo_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialequipopp_descripcion, $texto);

                $categoria = $value->categoria;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->catpartecuerpo_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialequipopp_descripcion, $texto);
            }
        }


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_EPP', $table);



        // TABLA - PUNTOS DE MUESTREO Y POE - RESUMEN VERSION 1 [PUNTOS DE MUESTREOS (NORMA)]
        //================================================================================


        $sql = DB::select('SELECT
                                -- *,
                                TABLA5.categoria_id,
                                TABLA5.categoria_nombre,
                                TABLA5.categoria_nombre_solo,
                                TABLA5.componente,
                                IF(MAX(TABLA5.MUESTREO_PPT) > 0, SUM(TABLA5.TOTAL_MUESTREOS), "ND") AS MUESTREO_PPT,
                                IF(MAX(TABLA5.MUESTREO_CT) > 0, SUM(TABLA5.TOTAL_MUESTREOS), "ND") AS MUESTREO_CT,
                                SUM(TABLA5.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS
                            FROM
                                (
                                    SELECT
                                        -- *,
                                        TABLA4.area_id,
                                        TABLA4.area_nombre,
                                        TABLA4.categoria_id,
                                        TABLA4.categoria_nombre,
                                        TABLA4.categoria_nombre_solo,
                                        TABLA4.id,
                                        TABLA4.sustancia_nombre,
                                        TABLA4.componente,
                                        -- (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) AS COMPONENTE_PONDERACION_TOTAL,       
                                        TABLA4.TOTAL2,
                                        TABLA4.PRIORIDAD2,
                                        TABLA4.NUMERO_MUESTREOS,
                                        TABLA4.MUESTREO_PPT,
                                        TABLA4.MUESTREO_CT,
                                        TABLA4.TOTAL_MUESTREOS
                                    FROM
                                        (
                                            SELECT
                                                -- *,
                                                TABLA3.area_id,
                                                TABLA3.area_nombre,
                                                TABLA3.categoria_id,
                                                TABLA3.categoria_nombre,
                                                TABLA3.categoria_nombre_solo,
                                                TABLA3.id,
                                                TABLA3.sustancia_nombre,
                                                -- datos ponderacion componente
                                                TABLA3.sustancia_cantidad,
                                                TABLA3.Umedida_ID,
                                                TABLA3.ponderacion_cantidad,
                                                TABLA3.ponderacion_riesgo,
                                                TABLA3.ponderacion_volatilidad,
                                                REPLACE(TABLA3.componente, "ˏ", ",") AS componente,
                                                TABLA3.cancerigeno,
                                                TABLA3.componente_porcentaje_real,
                                                TABLA3.componente_porcentaje,
                                                TABLA3.componente_cantidad,
                                                (
                                                    CASE
                                                        WHEN TABLA3.componente_cantidad = 0 THEN 0
                                                        WHEN TABLA3.cancerigeno = 1 THEN 5 -- cancerigeno
                                                        ELSE 
                                                            CASE
                                                                WHEN (TABLA3.Umedida_ID = 3 || TABLA3.Umedida_ID = 6) THEN -- M3 ó toneladas
                                                                    CASE
                                                                        WHEN TABLA3.componente_cantidad >= 1 THEN 4
                                                                        ELSE
                                                                            CASE
                                                                                WHEN (TABLA3.componente_cantidad * 1000) >= 1 THEN
                                                                                    CASE
                                                                                        WHEN (TABLA3.componente_cantidad * 1000) > 250 THEN 3
                                                                                        ELSE 2
                                                                                    END
                                                                                ELSE 1
                                                                            END
                                                                    END
                                                                WHEN (TABLA3.Umedida_ID = 2 || TABLA3.Umedida_ID = 5) THEN -- Litros ó Kilos
                                                                    CASE
                                                                        WHEN TABLA3.componente_cantidad >= 1 THEN
                                                                            CASE
                                                                                WHEN TABLA3.componente_cantidad > 250 THEN 3
                                                                                ELSE 2
                                                                            END
                                                                        ELSE 1
                                                                    END
                                                                ELSE 1
                                                            END
                                                    END
                                                ) AS componente_ponderacion_cantidad,
                                                -- / datos ponderacion componente
                                                TABLA3.TOTAL2,
                                                TABLA3.PRIORIDAD2,
                                                TABLA3.NUMERO_MUESTREOS,
                                                IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_PPT,
                                                IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_CT,
                                                (IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, 0))) AS TOTAL_MUESTREOS
                                            FROM
                                                (
                                                    SELECT
                                                        -- *,
                                                        TABLA2.area_id,
                                                        IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                        TABLA2.categoria_id,
                                                        -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre, " (", categoria_funcion, ")"), "Sin dato") AS categoria_nombre,
                                                        IFNULL(categoria_nombre, "Sin dato") AS categoria_nombre_solo,
                                                        IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                        TABLA2.id,
                                                        IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                        -- datos ponderacion componente
                                                        TABLA2.sustancia_cantidad,
                                                        TABLA2.Umedida_ID,
                                                        TABLA2.ponderacion_cantidad,
                                                        TABLA2.ponderacion_riesgo,
                                                        TABLA2.ponderacion_volatilidad,
                                                        catsustanciacomponente.catsustanciacomponente_nombre AS componente,
                                                        TABLA2.cancerigeno,
                                                        IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) AS componente_porcentaje_real,
                                                        IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0) AS componente_porcentaje,
                                                        ROUND((IFNULL(TABLA2.sustancia_cantidad, 0) * IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0)), 1) AS componente_cantidad,
                                                        -- / datos ponderacion componente
                                                        catsustanciacomponente.catsustanciacomponente_exposicionppt,
                                                        catsustanciacomponente.catsustanciacomponente_exposicionctop,
                                                        (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                        (
                                                            CASE
                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                                ELSE "Baja"
                                                            END
                                                        ) AS PRIORIDAD2,
                                                        (
                                                            CASE
                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                                    CASE
                                                                        WHEN tot_trabajadores > 100 THEN 20
                                                                        WHEN tot_trabajadores >= 51 THEN 15
                                                                        WHEN tot_trabajadores >= 26 THEN 8
                                                                        WHEN tot_trabajadores >= 16 THEN 5
                                                                        WHEN tot_trabajadores >= 9 THEN 3
                                                                        WHEN tot_trabajadores >= 3 THEN 2
                                                                        ELSE 1
                                                                    END
                                                                ELSE 
                                                                    CASE
                                                                        WHEN tot_trabajadores > 100 THEN 10
                                                                        WHEN tot_trabajadores >= 51 THEN 7
                                                                        WHEN tot_trabajadores >= 31 THEN 5
                                                                        WHEN tot_trabajadores >= 21 THEN 4
                                                                        WHEN tot_trabajadores >= 11 THEN 3
                                                                        WHEN tot_trabajadores >= 6 THEN 2
                                                                        ELSE 1
                                                                    END
                                                            END
                                                        ) AS NUMERO_MUESTREOS
                                                    FROM
                                                        (
                                                            SELECT
                                                                *,
                                                                (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                                (
                                                                    CASE
                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                        ELSE "Muy baja"
                                                                    END
                                                                ) AS PRIORIDAD
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        recsensorialquimicosinventario.recsensorial_id,
                                                                        recsensorialquimicosinventario.id,
                                                                        recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                        recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                        recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                        ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                        recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                        recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                        recsensorialcategoria.sumaHorasJornada AS horas_jornada,
                                                                        recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                        ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                        ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                        recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                        catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                        IFNULL((
                                                                            SELECT
                                                                                COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                            FROM
                                                                                catsustanciacomponente 
                                                                            WHERE
                                                                                catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                                AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                        ), 0) AS cancerigeno,
                                                                        (
                                                                            CASE
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                ELSE 0
                                                                            END
                                                                        ) AS ponderacion_cantidad,
                                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                        catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                        (
                                                                            CASE
                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                                ELSE 1
                                                                            END
                                                                        ) AS tot_personalexposicion,
                                                                        (
                                                                            CASE
                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                                ELSE 1
                                                                            END
                                                                        ) AS tot_tiempoexposicion,
                                                                        (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                        -- ,catsustancia.catestadofisicosustancia_id,
                                                                        -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                        -- catsustancia.catvolatilidad_id,
                                                                        -- catvolatilidad.catvolatilidad_tipo,
                                                                        -- catsustancia.catviaingresoorganismo_id,
                                                                        -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                        -- catsustancia.catcategoriapeligrosalud_id,
                                                                        -- catcategoriapeligrosalud.id,
                                                                        -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                        -- catsustancia.catgradoriesgosalud_id,
                                                                        -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                                    FROM
                                                                        recsensorialquimicosinventario
                                                                        LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                        LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                        AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                        LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                        LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                        LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                        LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                        LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                        LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                        LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                                                    WHERE
                                                                        recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                                                    GROUP BY
                                                                        recsensorialquimicosinventario.recsensorial_id,
                                                                        recsensorialquimicosinventario.id,
                                                                        recsensorialquimicosinventario.recsensorialarea_id,
                                                                        recsensorialarea.recsensorialarea_nombre,
                                                                        recsensorialquimicosinventario.recsensorialcategoria_id,
                                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                                        ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                                        recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                        ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                        recsensorialareacategorias.recsensorialareacategorias_total,
                                                                        ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                        ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                        recsensorialquimicosinventario.catsustancia_id,
                                                                        catsustancia.catsustancia_nombre,
                                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                        recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                        recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                        catvolatilidad.catvolatilidad_ponderacion,
                                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                                ) AS TABLA1
                                                            ORDER BY
                                                                area_id ASC,
                                                                categoria_id ASC,
                                                                TOTAL DESC
                                                        ) AS TABLA2
                                                        RIGHT JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = sustancia_id
                                                    WHERE
                                                        TOTAL >= 8
                                                    ORDER BY
                                                        TABLA2.area_id ASC,
                                                        TABLA2.categoria_id ASC,
                                                        TABLA2.id ASC
                                                ) AS TABLA3
                                            WHERE
                                                TOTAL2 >= 4
                                            GROUP BY
                                                TABLA3.area_id,
                                                TABLA3.area_nombre,
                                                TABLA3.categoria_id,
                                                TABLA3.categoria_nombre,
                                                TABLA3.categoria_nombre_solo,
                                                TABLA3.id,
                                                TABLA3.sustancia_nombre,
                                                -- datos ponderacion componente
                                                TABLA3.sustancia_cantidad,
                                                TABLA3.Umedida_ID,
                                                TABLA3.ponderacion_cantidad,
                                                TABLA3.ponderacion_riesgo,
                                                TABLA3.ponderacion_volatilidad,
                                                TABLA3.componente,
                                                TABLA3.cancerigeno,
                                                TABLA3.componente_porcentaje_real,
                                                TABLA3.componente_porcentaje,
                                                TABLA3.componente_cantidad,
                                                -- / datos ponderacion componente
                                                TABLA3.TOTAL2,
                                                TABLA3.PRIORIDAD2,
                                                TABLA3.NUMERO_MUESTREOS,
                                                TABLA3.catsustanciacomponente_exposicionppt,
                                                TABLA3.catsustanciacomponente_exposicionctop
                                            ORDER BY
                                                TABLA3.area_id ASC,
                                                TABLA3.categoria_id ASC,
                                                TABLA3.id ASC
                                        ) AS TABLA4
                                    WHERE
                                        TABLA4.TOTAL_MUESTREOS > 0
                                        -- AND (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) >= 8
                                ) AS TABLA5
                            GROUP BY
                                TABLA5.categoria_id,
                                TABLA5.categoria_nombre,
                                TABLA5.categoria_nombre_solo,
                                TABLA5.componente
                                -- TABLA5.MUESTREO_PPT,
                                -- TABLA5.MUESTREO_CT,
                                -- TABLA5.TOTAL_MUESTREOS
                            ORDER BY
                                TABLA5.categoria_id ASC,
                                TABLA5.categoria_nombre ASC,
                                TABLA5.componente ASC');



        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .36);
        $col_2 = ($width_table * .25);
        $col_3 = ($width_table * .13);
        $col_4 = ($width_table * .13);
        $col_5 = ($width_table * .13);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Grupo de exposición homogénea', $encabezado_texto);
        $table->addCell($col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Agente químico considerado para evaluar', $encabezado_texto);
        $table->addCell(($col_3 + $col_4), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Número de puntos por POE / punto a considerar', $encabezado_texto); // combina columna
        $table->addCell($col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Total puntos', $encabezado_texto);
        $table->addRow(); //fila
        $table->addCell($col_1, $continua_fila);
        $table->addCell($col_2, $continua_fila);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);
        $table->addCell($col_5, $continua_fila);


        // FILAS
        $categoria = 'xxx';
        foreach ($sql as $key => $value) {
            if ($categoria != $value->categoria_nombre_solo) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->categoria_nombre_solo, $textonegrita);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->componente, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);

                $categoria = $value->categoria_nombre_solo;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->componente, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);
            }
        }


        $plantillaword->setComplexBlock('TABLA_PUNTOS_MUESTRO_POE', $table);



        // TABLA CONTROLES CON LOS QUE CUENTAN EN EL AREA
        //================================================================================



        $sql = DB::select('SELECT
                                recsensorialarea.recsensorial_id,
                                recsensorialarea.id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                recsensorialarea.recsensorialarea_controlestecnicos,
                                recsensorialarea.recsensorialarea_controlesadministrativos 
                            FROM
                                recsensorialarea 
                            WHERE
                                recsensorialarea.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialarea.id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 13500;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .30);
        $col_2 = ($width_table * .35);
        $col_3 = ($width_table * .35);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Controles técnicos', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Controles administrativos', $encabezado_texto);


        // FILAS
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
            $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_controlestecnicos, $texto);
            $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_controlesadministrativos, $texto);
        }


        $plantillaword->setComplexBlock('TABLA_CONTROLES_AREA', $table);



        // TABLA RESULTADOS - VERSION 1 [PUNTOS DE MUESTREOS Y TIPO DE INSTALACION (NORMA)]
        //================================================================================



        $sql = DB::select('SELECT
                                TABLA6.componente,
                                COUNT(TABLA6.componente) AS tot_registros,
                                IF(MAX(TABLA6.MUESTREO_PPT) > 0, SUM(TABLA6.TOTAL_MUESTREOS), "ND") AS MUESTREO_PPT,
                                IF(MAX(TABLA6.MUESTREO_CT) > 0, SUM(TABLA6.TOTAL_MUESTREOS), "ND") AS MUESTREO_CT,
                                SUM(TABLA6.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS,
                                (
                                    CASE
                                        -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "Extra grande"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "Grande"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "Mediana"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "Chica"
                                        ELSE "Extra chica"
                                    END
                                ) AS tipoinstalacion,
                                (
                                    CASE
                                        -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "XG"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "G"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "M"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "CH"
                                        ELSE "XC"
                                    END
                                ) AS tipoinstalacion_abreviacion,
                                (
                                    CASE
                                        -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "#DF0101"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "#FF8000"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "#FFD700"
                                        WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "#5FB404"
                                        ELSE "#0080FF"
                                    END
                                ) AS tipoinstalacion_color
                            FROM
                                (
                                    SELECT
                                        -- *,
                                        -- TABLA5.area_id,
                                        -- TABLA5.area_nombre,
                                        TABLA5.categoria_id,
                                        TABLA5.categoria_nombre,
                                        -- TABLA5.id,
                                        -- TABLA5.sustancia_nombre,
                                        TABLA5.componente,
                                        -- IF(TABLA5.MUESTREO_PPT > 0, TABLA5.TOTAL_MUESTREOS, "ND") AS MUESTREO_PPT,
                                        -- IF(TABLA5.MUESTREO_CT > 0, TABLA5.TOTAL_MUESTREOS, "ND") AS MUESTREO_CT,
                                        IF(TABLA5.MUESTREO_PPT > 0, 1, 0) AS MUESTREO_PPT,
                                        IF(TABLA5.MUESTREO_CT > 0, 1, 0) AS MUESTREO_CT,
                                        SUM(TABLA5.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS
                                    FROM
                                        (
                                            SELECT
                                                -- *,
                                                TABLA4.area_id,
                                                TABLA4.area_nombre,
                                                TABLA4.categoria_id,
                                                TABLA4.categoria_nombre,
                                                TABLA4.id,
                                                TABLA4.sustancia_nombre,
                                                TABLA4.componente,
                                                -- (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) AS COMPONENTE_PONDERACION_TOTAL,       
                                                TABLA4.TOTAL2,
                                                TABLA4.PRIORIDAD2,
                                                TABLA4.NUMERO_MUESTREOS,
                                                TABLA4.MUESTREO_PPT,
                                                TABLA4.MUESTREO_CT,
                                                TABLA4.TOTAL_MUESTREOS
                                            FROM
                                                (
                                                    SELECT
                                                        -- *,
                                                        TABLA3.area_id,
                                                        TABLA3.area_nombre,
                                                        TABLA3.categoria_id,
                                                        TABLA3.categoria_nombre,
                                                        TABLA3.id,
                                                        TABLA3.sustancia_nombre,
                                                        -- datos ponderacion componente
                                                        TABLA3.sustancia_cantidad,
                                                        TABLA3.Umedida_ID,
                                                        TABLA3.ponderacion_cantidad,
                                                        TABLA3.ponderacion_riesgo,
                                                        TABLA3.ponderacion_volatilidad,
                                                        REPLACE(TABLA3.componente, "ˏ", ",") AS componente,
                                                        TABLA3.cancerigeno,
                                                        TABLA3.componente_porcentaje_real,
                                                        TABLA3.componente_porcentaje,
                                                        TABLA3.componente_cantidad,
                                                        (
                                                            CASE
                                                                WHEN TABLA3.componente_cantidad = 0 THEN 0
                                                                WHEN TABLA3.cancerigeno = 1 THEN 5 -- cancerigeno
                                                                ELSE 
                                                                    CASE
                                                                        WHEN (TABLA3.Umedida_ID = 3 || TABLA3.Umedida_ID = 6) THEN -- M3 ó toneladas
                                                                            CASE
                                                                                WHEN TABLA3.componente_cantidad >= 1 THEN 4
                                                                                ELSE
                                                                                    CASE
                                                                                        WHEN (TABLA3.componente_cantidad * 1000) >= 1 THEN
                                                                                            CASE
                                                                                                WHEN (TABLA3.componente_cantidad * 1000) > 250 THEN 3
                                                                                                ELSE 2
                                                                                            END
                                                                                        ELSE 1
                                                                                    END
                                                                            END
                                                                        WHEN (TABLA3.Umedida_ID = 2 || TABLA3.Umedida_ID = 5) THEN -- Litros ó Kilos
                                                                            CASE
                                                                                WHEN TABLA3.componente_cantidad >= 1 THEN
                                                                                    CASE
                                                                                        WHEN TABLA3.componente_cantidad > 250 THEN 3
                                                                                        ELSE 2
                                                                                    END
                                                                                ELSE 1
                                                                            END
                                                                        ELSE 1
                                                                    END
                                                            END
                                                        ) AS componente_ponderacion_cantidad,
                                                        -- / datos ponderacion componente
                                                        TABLA3.TOTAL2,
                                                        TABLA3.PRIORIDAD2,
                                                        TABLA3.NUMERO_MUESTREOS,
                                                        IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_PPT,
                                                        IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_CT,
                                                        (IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, 0))) AS TOTAL_MUESTREOS
                                                    FROM
                                                        (
                                                            SELECT
                                                                -- *,
                                                                TABLA2.area_id,
                                                                IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                                TABLA2.categoria_id,
                                                                -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre, " (", categoria_funcion, ")"), "Sin dato") AS categoria_nombre,
                                                                IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                                TABLA2.id,
                                                                IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                                -- datos ponderacion componente
                                                                TABLA2.sustancia_cantidad,
                                                                TABLA2.Umedida_ID,
                                                                TABLA2.ponderacion_cantidad,
                                                                TABLA2.ponderacion_riesgo,
                                                                TABLA2.ponderacion_volatilidad,
                                                                catsustanciacomponente.catsustanciacomponente_nombre AS componente,
                                                                TABLA2.cancerigeno,
                                                                IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) AS componente_porcentaje_real,
                                                                IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0) AS componente_porcentaje,
                                                                ROUND((IFNULL(TABLA2.sustancia_cantidad, 0) * IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0)), 1) AS componente_cantidad,
                                                                -- / datos ponderacion componente
                                                                catsustanciacomponente.catsustanciacomponente_exposicionppt,
                                                                catsustanciacomponente.catsustanciacomponente_exposicionctop,
                                                                (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                                (
                                                                    CASE
                                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                                        ELSE "Baja"
                                                                    END
                                                                ) AS PRIORIDAD2,
                                                                (
                                                                    CASE
                                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                                            CASE
                                                                                WHEN tot_trabajadores > 100 THEN 20
                                                                                WHEN tot_trabajadores >= 51 THEN 15
                                                                                WHEN tot_trabajadores >= 26 THEN 8
                                                                                WHEN tot_trabajadores >= 16 THEN 5
                                                                                WHEN tot_trabajadores >= 9 THEN 3
                                                                                WHEN tot_trabajadores >= 3 THEN 2
                                                                                ELSE 1
                                                                            END
                                                                        ELSE 
                                                                            CASE
                                                                                WHEN tot_trabajadores > 100 THEN 10
                                                                                WHEN tot_trabajadores >= 51 THEN 7
                                                                                WHEN tot_trabajadores >= 31 THEN 5
                                                                                WHEN tot_trabajadores >= 21 THEN 4
                                                                                WHEN tot_trabajadores >= 11 THEN 3
                                                                                WHEN tot_trabajadores >= 6 THEN 2
                                                                                ELSE 1
                                                                            END
                                                                    END
                                                                ) AS NUMERO_MUESTREOS
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        *,
                                                                        (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                                        (
                                                                            CASE
                                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                                ELSE "Muy baja"
                                                                            END
                                                                        ) AS PRIORIDAD
                                                                    FROM
                                                                        (
                                                                            SELECT
                                                                                recsensorialquimicosinventario.recsensorial_id,
                                                                                recsensorialquimicosinventario.id,
                                                                                recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                                recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                                recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                                recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                                recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                                recsensorialcategoria.sumaHorasJornada AS horas_jornada,
                                                                                recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                                recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                                catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                                IFNULL((
                                                                                    SELECT
                                                                                        COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                                    FROM
                                                                                        catsustanciacomponente 
                                                                                    WHERE
                                                                                        catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                                        AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                                ), 0) AS cancerigeno,
                                                                                (
                                                                                    CASE
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                        ELSE 0
                                                                                    END
                                                                                ) AS ponderacion_cantidad,
                                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                                catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                                (
                                                                                    CASE
                                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                                        ELSE 1
                                                                                    END
                                                                                ) AS tot_personalexposicion,
                                                                                (
                                                                                    CASE
                                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                                        ELSE 1
                                                                                    END
                                                                                ) AS tot_tiempoexposicion,
                                                                                (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                                -- ,catsustancia.catestadofisicosustancia_id,
                                                                                -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                                -- catsustancia.catvolatilidad_id,
                                                                                -- catvolatilidad.catvolatilidad_tipo,
                                                                                -- catsustancia.catviaingresoorganismo_id,
                                                                                -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                                -- catsustancia.catcategoriapeligrosalud_id,
                                                                                -- catcategoriapeligrosalud.id,
                                                                                -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                                -- catsustancia.catgradoriesgosalud_id,
                                                                                -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                                            FROM
                                                                                recsensorialquimicosinventario
                                                                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                                LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                                AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                                LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                                                            WHERE
                                                                                recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                                                            GROUP BY
                                                                                recsensorialquimicosinventario.recsensorial_id,
                                                                                recsensorialquimicosinventario.id,
                                                                                recsensorialquimicosinventario.recsensorialarea_id,
                                                                                recsensorialarea.recsensorialarea_nombre,
                                                                                recsensorialquimicosinventario.recsensorialcategoria_id,
                                                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                                                recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                                recsensorialareacategorias.recsensorialareacategorias_total,
                                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                                recsensorialquimicosinventario.catsustancia_id,
                                                                                catsustancia.catsustancia_nombre,
                                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                                recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                                recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                                catvolatilidad.catvolatilidad_ponderacion,
                                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                                        ) AS TABLA1
                                                                    ORDER BY
                                                                        area_id ASC,
                                                                        categoria_id ASC,
                                                                        TOTAL DESC
                                                                ) AS TABLA2
                                                                RIGHT JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = sustancia_id
                                                            WHERE
                                                                TOTAL >= 8
                                                            ORDER BY
                                                                TABLA2.area_id ASC,
                                                                TABLA2.categoria_id ASC,
                                                                TABLA2.id ASC
                                                        ) AS TABLA3
                                                    WHERE
                                                        TOTAL2 >= 4
                                                    GROUP BY
                                                        TABLA3.area_id,
                                                        TABLA3.area_nombre,
                                                        TABLA3.categoria_id,
                                                        TABLA3.categoria_nombre,
                                                        TABLA3.id,
                                                        TABLA3.sustancia_nombre,
                                                        -- datos ponderacion componente
                                                        TABLA3.sustancia_cantidad,
                                                        TABLA3.Umedida_ID,
                                                        TABLA3.ponderacion_cantidad,
                                                        TABLA3.ponderacion_riesgo,
                                                        TABLA3.ponderacion_volatilidad,
                                                        TABLA3.componente,
                                                        TABLA3.cancerigeno,
                                                        TABLA3.componente_porcentaje_real,
                                                        TABLA3.componente_porcentaje,
                                                        TABLA3.componente_cantidad,
                                                        -- / datos ponderacion componente
                                                        TABLA3.TOTAL2,
                                                        TABLA3.PRIORIDAD2,
                                                        TABLA3.NUMERO_MUESTREOS,
                                                        TABLA3.catsustanciacomponente_exposicionppt,
                                                        TABLA3.catsustanciacomponente_exposicionctop
                                                    ORDER BY
                                                        TABLA3.area_id ASC,
                                                        TABLA3.categoria_id ASC,
                                                        TABLA3.id ASC
                                                ) AS TABLA4
                                            WHERE
                                                TABLA4.TOTAL_MUESTREOS > 0
                                                -- AND (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) >= 8
                                        ) AS TABLA5
                                    GROUP BY
                                        -- TABLA5.area_id,
                                        -- TABLA5.area_nombre,
                                        TABLA5.categoria_id,
                                        TABLA5.categoria_nombre,
                                        -- TABLA5.id,
                                        -- TABLA5.sustancia_nombre,
                                        TABLA5.componente,
                                        TABLA5.MUESTREO_PPT,
                                        TABLA5.MUESTREO_CT,
                                        TABLA5.TOTAL_MUESTREOS
                                    ORDER BY
                                        -- TABLA5.area_id ASC,
                                        TABLA5.categoria_id ASC,
                                        -- TABLA5.id ASC,
                                        TABLA5.componente ASC
                                ) AS TABLA6
                            GROUP BY
                                TABLA6.componente
                            ORDER BY
                                SUM(TABLA6.TOTAL_MUESTREOS) DESC,
                                TABLA6.componente ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .40);
        $col_2 = ($width_table * .14);
        $col_3 = ($width_table * .14);
        $col_4 = ($width_table * .12);
        $col_5 = ($width_table * .20);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Agente químico', $encabezado_texto);
        $table->addCell(($col_2 + $col_3), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Puntos de muestreos', $textototal); // combina columna
        $table->addCell($col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Total puntos', $encabezado_texto);
        $table->addCell($col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Tipo de instalación', $encabezado_texto);
        $table->addRow(); //fila
        $table->addCell($col_1, $continua_fila);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('PPT', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('CT', $encabezado_texto);
        $table->addCell($col_4, $continua_fila);
        $table->addCell($col_5, $continua_fila);


        // FILAS
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->componente, $texto);
            $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->MUESTREO_PPT, $texto);
            $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->MUESTREO_CT, $texto);
            $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->TOTAL_MUESTREOS, $textonegrita);
            $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->tipoinstalacion, $texto);
        }


        $plantillaword->setComplexBlock('TABLA_RESULTADOS', $table);



        // TABLA RESULTADOS - CONCLUSION
        //================================================================================


        // CREAR TABLA
        $table = null;
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .30);
        $col_2 = ($width_table * .70);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Sustancia', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento Normativo', $encabezado_texto);


        // FILAS
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila

            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->componente, $texto);

            if (($key + 0) == 0) {
                $table->addCell($col_2, $combinar_fila)->addTextRun($justificado)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>NOM-010-STPS-2014</w:t></w:r><w:r><w:t>, Agentes químicos contaminantes del ambiente laboral-Reconocimiento, evaluación y control.', $texto);
            } else {
                $table->addCell($col_2, $continua_fila);
            }
        }


        $plantillaword->setComplexBlock('TABLA_CONCLUSION_SUSTANCIA', $table);



        // RESPONSABLES
        //================================================================================


        // RESPONSABLE 1, FOTO DOCUMENTO
        if ($recsensorial->recsensorial_repquimicos1doc) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_repquimicos1doc))) {
                $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $recsensorial->recsensorial_repquimicos1doc), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'SIN IMAGEN.');
            }
        } else {
            $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'SIN IMAGEN.');
        }


        $plantillaword->setValue('REPONSABLE1', $recsensorial->recsensorial_repquimicos1nombre . "<w:br/>" . $recsensorial->recsensorial_repquimicos1cargo);


        // RESPONSABLE 2, FOTO DOCUMENTO
        if ($recsensorial->recsensorial_repquimicos2doc) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_repquimicos2doc))) {
                $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $recsensorial->recsensorial_repquimicos2doc), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'SIN IMAGEN.');
            }
        } else {
            $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'SIN IMAGEN.');
        }


        $plantillaword->setValue('REPONSABLE2', $recsensorial->recsensorial_repquimicos2nombre . "<w:br/>" . $recsensorial->recsensorial_repquimicos2cargo);



        // TABLA ANEXO 1, Memoria fotográfica  - CREAR VARIABLES
        //================================================================================



        $fotos = DB::select('SELECT
                                recsensorialevidencias.recsensorial_id,
                                recsensorialevidencias.id,
                                recsensorialevidencias.cat_prueba_id,
                                cat_prueba.catPrueba_orden,
                                recsensorialevidencias.recsensorialevidencias_tipo,
                                recsensorialevidencias.recsensorialevidencias_descripcion,
                                recsensorialevidencias.recsensorialevidencias_foto 
                            FROM
                                recsensorialevidencias
                                LEFT JOIN cat_prueba ON recsensorialevidencias.cat_prueba_id = cat_prueba.id
                            WHERE
                                recsensorialevidencias.recsensorial_id = ' . $recsensorial_id . ' 
                                AND recsensorialevidencias.cat_prueba_id = 15 -- Quimicos
                                AND recsensorialevidencias.recsensorialevidencias_tipo = 1 -- 1 = FOTOS, 2 = PLANOS
                            ORDER BY
                                cat_prueba.catPrueba_orden ASC,
                                recsensorialevidencias.id ASC');


        if (count($fotos) > 0) {
            $col_1 = 4970;
            $col_2 = 4970;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

            $table->addRow(500, array('tblHeader' => true));
            $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => '000000', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1,))->addTextRun($centrado)->addText('Memoria fotográfica', array('color' => '000000', 'size' => 12, 'bold' => true, 'name' => $fuente));
            $table->addRow(500, array('tblHeader' => true));
            $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Reconocimiento de agentes químicos', $encabezado_texto);


            for ($i = 0; $i < count($fotos); $i += 4) {
                $foto1 = '';
                $descripcion1 = '';
                if ($i < count($fotos)) {
                    $foto1 = '${FOTO_' . $i . '}';
                    $descripcion1 = '${FOTO_' . $i . '_DESCRIPCION}';
                }

                $foto2 = '';
                $descripcion2 = '';
                if (($i + 1) < count($fotos)) {
                    $foto2 = '${FOTO_' . ($i + 1) . '}';
                    $descripcion2 = '${FOTO_' . ($i + 1) . '_DESCRIPCION}';
                }

                $foto3 = '';
                $descripcion3 = '';
                if (($i + 2) < count($fotos)) {
                    $foto3 = '${FOTO_' . ($i + 2) . '}';
                    $descripcion3 = '${FOTO_' . ($i + 2) . '_DESCRIPCION}';
                }

                $foto4 = '';
                $descripcion4 = '';
                if (($i + 3) < count($fotos)) {
                    $foto4 = '${FOTO_' . ($i + 3) . '}';
                    $descripcion4 = '${FOTO_' . ($i + 3) . '_DESCRIPCION}';
                }

                // $table->addRow(); //fila
                // $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Reconocimiento de agentes físicos', $encabezado_texto);
                $table->addRow(); //fila
                $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($foto1, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($foto2, $texto);
                $table->addRow(1000); //fila
                $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($descripcion1, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($descripcion2, $texto);

                if (($i + 2) < count($fotos)) {
                    // $table->addRow(); //fila
                    // $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Reconocimiento de agentes físicos', $encabezado_texto);
                    $table->addRow(); //fila
                    $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($foto3, $texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($foto4, $texto);
                    $table->addRow(1000); //fila
                    $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($descripcion3, $texto);
                    $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($descripcion4, $texto);
                }
            }


            $plantillaword->setComplexBlock('TABLA_MEMORIA_FOTOGRAFICA', $table);
        } else {
            $plantillaword->setValue('TABLA_MEMORIA_FOTOGRAFICA', 'NO HAY FOTOS QUE MOSTRAR');
        }



        // ANEXO 2, Planos de ubicación - CREAR VARIABLES
        //================================================================================



        $planos = DB::select('SELECT
                                    recsensorialevidencias.recsensorial_id,
                                    recsensorialevidencias.id,
                                    recsensorialevidencias.cat_prueba_id,
                                    cat_prueba.catPrueba_orden,
                                    recsensorialevidencias.recsensorialevidencias_tipo,
                                    recsensorialevidencias.recsensorialevidencias_descripcion,
                                    recsensorialevidencias.recsensorialevidencias_foto 
                                FROM
                                    recsensorialevidencias
                                    LEFT JOIN cat_prueba ON recsensorialevidencias.cat_prueba_id = cat_prueba.id
                                WHERE
                                    recsensorialevidencias.recsensorial_id = ' . $recsensorial_id . ' 
                                    AND recsensorialevidencias.cat_prueba_id = 15 -- Quimicos
                                    AND recsensorialevidencias.recsensorialevidencias_tipo = 2 -- 1 = FOTOS, 2 = PLANOS
                                ORDER BY
                                    cat_prueba.catPrueba_orden ASC,
                                    recsensorialevidencias.id ASC');


        $planos1_variables = '';
        $planos2_variables = '';
        if (count($planos) > 0) {
            foreach ($planos as $key => $plano) {
                if (($key + 0) > 0) {
                    if (($key + 1) < count($planos)) // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)
                    {
                        $planos1_variables .= '${PLANO1_' . $key . '_TITULO}<w:br/>${PLANO1_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                        $planos2_variables .= '${PLANO2_' . $key . '_TITULO}<w:br/>${PLANO2_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                    } else {
                        $planos1_variables .= '${PLANO1_' . $key . '_TITULO}<w:br/>${PLANO1_' . $key . '_FOTO}<w:br/>';
                        $planos2_variables .= '${PLANO2_' . $key . '_TITULO}<w:br/>${PLANO2_' . $key . '_FOTO}<w:br/>';
                    }
                } else {
                    if (($key + 1) < count($planos)) // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)
                    {
                        $planos1_variables .= '${PLANO1_' . $key . '_TITULO}<w:br/>${PLANO1_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                        $planos2_variables .= '${PLANO2_' . $key . '_TITULO}<w:br/>${PLANO2_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                    } else {
                        $planos1_variables .= '${PLANO1_' . $key . '_TITULO}<w:br/>${PLANO1_' . $key . '_FOTO}<w:br/>';
                        $planos2_variables .= '${PLANO2_' . $key . '_TITULO}<w:br/>${PLANO2_' . $key . '_FOTO}<w:br/>';
                    }
                }
            }

            $plantillaword->setValue('PLANOS_1', $planos1_variables);
            $plantillaword->setValue('PLANOS_2', $planos2_variables);
        } else {
            $plantillaword->setValue('PLANOS_1', 'NO HAY PLANOS QUE MOSTRAR');
            $plantillaword->setValue('PLANOS_2', 'NO HAY PLANOS QUE MOSTRAR');
        }



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CREAR WORD TEMPORAL


        // GUARDAR
        Storage::makeDirectory('reportes/recsensorial'); //crear directorio
        $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_folioquimico . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

        // sleep(1);

        // ABRIR NUEVA PLANTILLA
        $plantillaword = new TemplateProcessor(storage_path('app/reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_folioquimico . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        // TABLA ANEXO 1, Memoria fotográfica - AGREGAR FOTOS
        //================================================================================


        for ($i = 0; $i < count($fotos); $i += 4) {
            if ($i < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . $i, array('path' => storage_path('app/' . $fotos[$i]->recsensorialevidencias_foto), 'height' => 260, 'width' => 260, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . $i, 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . $i . '_DESCRIPCION', $fotos[$i]->recsensorialevidencias_descripcion);
            }


            if (($i + 1) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 1), array('path' => storage_path('app/' . $fotos[($i + 1)]->recsensorialevidencias_foto), 'height' => 260, 'width' => 260, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . ($i + 1), 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . ($i + 1) . '_DESCRIPCION', $fotos[($i + 1)]->recsensorialevidencias_descripcion);
            }


            if (($i + 2) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 2), array('path' => storage_path('app/' . $fotos[($i + 2)]->recsensorialevidencias_foto), 'height' => 260, 'width' => 260, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . ($i + 2), 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . ($i + 2) . '_DESCRIPCION', $fotos[($i + 2)]->recsensorialevidencias_descripcion);
            }


            if (($i + 3) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 3), array('path' => storage_path('app/' . $fotos[($i + 3)]->recsensorialevidencias_foto), 'height' => 260, 'width' => 260, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . ($i + 3), 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . ($i + 3) . '_DESCRIPCION', $fotos[($i + 3)]->recsensorialevidencias_descripcion);
            }
        }


        // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - AGREGAR FOTOS
        //================================================================================


        for ($i = 0; $i < count($planos); $i++) {
            if (Storage::exists($planos[$i]->recsensorialevidencias_foto)) {
                $plantillaword->setValue('PLANO1_' . $i . '_TITULO', $planos[$i]->recsensorialevidencias_descripcion);
                $plantillaword->setImageValue('PLANO1_' . $i . '_FOTO', array('path' => storage_path('app/' . $planos[$i]->recsensorialevidencias_foto), 'height' => 650, 'width' => 600, 'ratio' => false));

                $plantillaword->setValue('PLANO2_' . $i . '_TITULO', $planos[$i]->recsensorialevidencias_descripcion);
                $plantillaword->setImageValue('PLANO2_' . $i . '_FOTO', array('path' => storage_path('app/' . $planos[$i]->recsensorialevidencias_foto), 'height' => 680, 'width' => 600, 'ratio' => false));
            } else {
                $plantillaword->setValue('PLANO1_' . $i . '_TITULO', '');
                $plantillaword->setValue('PLANO1_' . $i . '_FOTO', 'NO SE ENCONTRÓ EL PLANO ' . $i);

                $plantillaword->setValue('PLANO2_' . $i . '_TITULO', '');
                $plantillaword->setValue('PLANO2_' . $i . '_FOTO', 'NO SE ENCONTRÓ EL PLANO ' . $i);
            }
        }



        // ARCHIVO PDF's ANEXOS
        //================================================================================


        $anexos = DB::select('SELECT
                                    recsensorialanexo.recsensorial_id,
                                    recsensorialanexo.id,
                                    recsensorialanexo.recsensorialanexo_tipo,
                                    recsensorialanexo.acreditacion_id,
                                    acreditacion.acreditacion_Entidad,
                                    REPLACE(acreditacion.acreditacion_Numero, "/", "-") AS acreditacion_Numero,
                                    acreditacion.acreditacion_Vigencia,
                                    acreditacion.acreditacion_SoportePDF 
                                FROM
                                    recsensorialanexo
                                    LEFT JOIN acreditacion ON recsensorialanexo.acreditacion_id = acreditacion.id
                                WHERE
                                    recsensorialanexo.recsensorial_id = ' . $recsensorial_id . ' 
                                    AND recsensorialanexo.recsensorialanexo_tipo = 2'); // 1 = FISICOS, 2 = QUIMICOS



        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // GUARDAR Y DESCARGAR INFORME FINAL


        $informe_nombre = 'Reconocimiento de agentes químicos ' . $recsensorial->recsensorial_folioquimico . ' (' . $recsensorial->recsensorial_instalacion . ').docx';


        // GUARDAR WORD FINAL
        $plantillaword->saveAs(storage_path('app/reportes/recsensorial/' . $informe_nombre)); //crear archivo word


        // ELIMINAR TEMPORAL
        if (Storage::exists('reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_folioquimico . '_TEMPORAL.docx')) {
            Storage::delete('reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_folioquimico . '_TEMPORAL.docx');
        }



        //================================================================================
        // CREAR .ZIP



        // Define Dir Folder
        $zip_ruta = storage_path('app/reportes/recsensorial');

        // Zip File Name
        $zip_nombre = 'Reconocimiento de agentes químicos ' . $recsensorial->recsensorial_folioquimico . ' (' . $recsensorial->recsensorial_instalacion . ') + Anexos.zip';

        // Create ZipArchive Obj
        $zip = new ZipArchive;

        if ($zip->open($zip_ruta . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE) {
            // Add File in ZipArchive
            $zip->addFile(storage_path('app/reportes/recsensorial/' . $informe_nombre), $informe_nombre); //Word

            foreach ($anexos as $file) {
                if (Storage::exists($file->acreditacion_SoportePDF)) {
                    $extencion = explode(".", $file->acreditacion_SoportePDF);
                    $zip->addFile(storage_path('app/' . $file->acreditacion_SoportePDF), $file->acreditacion_Entidad . ' (' . $file->acreditacion_Numero . ').' . $extencion[1]); // Pdf Anexos
                }
            }

            // Close ZipArchive     
            $zip->close();
        }

        // Set Header
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );


        $zip_rutacompleta = $zip_ruta . '/' . $zip_nombre;



        //================================================================================


        // ELIMINAR INFORME word final (PORQUE YA ESTÁ EN EL ZIP)
        if (Storage::exists('reportes/recsensorial/' . $informe_nombre)) {
            Storage::delete('reportes/recsensorial/' . $informe_nombre);
        }



        // FINALIZAR
        //================================================================================


        try {
            // GUARDAR
            //-------------------------------------------
            // Storage::makeDirectory('reportes/recsensorial'); //crear directorio
            // $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Reconocimiento de agentes químicos '.$recsensorial->recsensorial_folioquimico.' ('.$recsensorial->recsensorial_instalacion.').docx')); //crear archivo word
            // $plantillaword->saveAs(public_path('app/reportes/recsensorial/Reconocimiento de agentes químicos '.$recsensorial->recsensorial_folioquimico.' ('.$recsensorial->recsensorial_instalacion.').docx'));


            // DESCARGA
            //-------------------------------------------

            // ARCHIVO
            // return response()->download(storage_path('app/reportes/recsensorial/Reconocimiento de agentes químicos '.$recsensorial->recsensorial_folioquimico.' ('.$recsensorial->recsensorial_instalacion.').docx'))->deleteFileAfterSend(true);
            // return response()->download(storage_path('app/reportes/recsensorial/'.$informe_nombre))->deleteFileAfterSend(true);


            // ZIP
            if (file_exists($zip_rutacompleta)) {
                return response()->download($zip_rutacompleta, $zip_nombre, $headers)->deleteFileAfterSend(true);
            }


            // RESPUESTA
            //-------------------------------------------
            // $dato["msj"] = 'Informacion consultada correctamente';
            // return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
