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


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class recsensorialreportewordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $recsensorial_tipo
     * @return \Illuminate\Http\Response
     */
    public function recsensorialreportedescarga($recsensorial_id, $recsensorial_tipo)
    {
        try {
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
            // dd($recsensorial->all());


            if (($recsensorial_tipo + 0) == 1) //Fisicos
            {
                // if (($recsensorial->recsensorial_alcancequimico+0) != 1)
                // {
                //     $recsensorial->update([
                //         'recsensorial_fisicosimprimirbloqueado' => 1
                //         , 'recsensorial_quimicosimprimirbloqueado' => 1
                //     ]);
                // }
                // else
                // {
                //     $recsensorial->update([
                //         'recsensorial_fisicosimprimirbloqueado' => 1
                //     ]);
                // }


                $recsensorial->update([
                    'recsensorial_fisicosimprimirbloqueado' => 1
                ]);
            } else {
                // if (($recsensorial->recsensorial_alcancefisico+0) != 1)
                // {
                //     $recsensorial->update([
                //         'recsensorial_fisicosimprimirbloqueado' => 1
                //         , 'recsensorial_quimicosimprimirbloqueado' => 1
                //     ]);
                // }
                // else
                // {
                //     $recsensorial->update([
                //         'recsensorial_quimicosimprimirbloqueado' => 1
                //     ]);
                // }


                $recsensorial->update([
                    'recsensorial_quimicosimprimirbloqueado' => 1
                ]);
            }


            $dato["recsensorial_bloqueado"] = 0;
            if (($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 1 && ($recsensorial->recsensorial_quimicosimprimirbloqueado + 0) == 1) {
                $recsensorial->update([
                    'recsensorial_bloqueado' => 1
                ]);

                $dato["recsensorial_bloqueado"] = 1;
            }


            // respuesta            
            $dato["msj"] = 'Reconocimiento bloqueado correctamente para descarga';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["recsensorial_bloqueado"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialreporte1word($recsensorial_id)
    {
        // dd('Prueba reconocimiento '.$recsensorial_id);


        $No = 1;
        // Datos reconocimiento sensorial
        // $recsensorial = DB::select('SET lc_time_names = "es_MX"');
        $recsensorial = DB::select('SELECT
                                        recsensorial.id,
                                        recsensorial.recsensorial_foliofisico,
                                        recsensorial.recsensorial_folioquimico,
                                        cliente.id AS cliente_id,
                                        cliente.cliente_numerocontrato,
                                        catregion.catregion_nombre,
                                        catgerencia.catgerencia_nombre,
                                        catactivo.catactivo_nombre,
                                        recsensorial.recsensorial_empresa,
                                        recsensorial.recsensorial_codigopostal,
                                        recsensorial.recsensorial_rfc,
                                        recsensorial.recsensorial_representantelegal,
                                        recsensorial.recsensorial_representanteseguridad,
                                        recsensorial.recsensorial_instalacion,
                                        recsensorial.recsensorial_direccion,
                                        recsensorial.recsensorial_coordenadas,
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
                                        recsensorial.recsensorial_eliminado 
                                    FROM
                                        recsensorial
                                        LEFT JOIN cliente ON recsensorial.cliente_id = cliente.id
                                        LEFT JOIN catregion ON recsensorial.catregion_id = catregion.id
                                        LEFT JOIN catgerencia ON recsensorial.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON recsensorial.catactivo_id = catactivo.id 
                                    WHERE
                                        recsensorial.id = ' . $recsensorial_id);
        // dd($recsensorial[0]->recsensorial_descripcionproceso);


        // LEER PLANTILLA WORD
        //================================================================================


        // $plantillaword = new TemplateProcessor(public_path('/plantillas_reportes/reconocimiento_sensorial/plantila_recsensorial_reporte1.docx'));    //Ruta carpeta public
        $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_fisicos.docx')); //Ruta carpeta storage


        // ESCRIBIR DATOS GENERALES
        //================================================================================

        $cliente = clienteModel::findOrFail($recsensorial[0]->cliente_id);


        if ($cliente->cliente_plantillalogoizquierdo) {
            if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoizquierdo))) {
                $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoizquierdo), 'width' => 180, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoizquierdo), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
        }


        if ($cliente->cliente_plantillalogoderecho) {
            if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoderecho))) {
                $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoderecho), 'width' => 180, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoderecho), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
            }
        } else {
            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
        }


        $titulo_partida = clientepartidasModel::where('cliente_id', $recsensorial[0]->cliente_id)
            ->where('clientepartidas_tipo', 1) // reconocimiento
            ->where('catprueba_id', 1) // fisicos
            ->orderBy('updated_at', 'DESC')
            ->get();


        if (count($titulo_partida) > 0) {
            $plantillaword->setValue('TITULO_INFORME', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripción));
        } else {
            $plantillaword->setValue('TITULO_INFORME', 'TITULO DEL INFORME<w:br/>(INFORMACIÓN NO CARGADA EN EL SOFTWARE)');
        }


        $plantillaword->setValue('DESCRIPCION_CONTRATO', str_replace("\n", "<w:br/>", $cliente->cliente_descripcioncontrato));
        $plantillaword->setValue('PIE_PAGINA', str_replace("\n", "<w:br/>", $cliente->cliente_plantillapiepagina));


        $plantillaword->setValue('instalación', $recsensorial[0]->recsensorial_instalacion);
        $plantillaword->setValue('contrato', $recsensorial[0]->cliente_numerocontrato);
        $plantillaword->setValue('region', $recsensorial[0]->catregion_nombre);
        $plantillaword->setValue('gerencia', $recsensorial[0]->catgerencia_nombre);
        $plantillaword->setValue('activo', $recsensorial[0]->catactivo_nombre);
        $plantillaword->setValue('empresa_nombre', $recsensorial[0]->recsensorial_empresa);
        $plantillaword->setValue('empresa_rfc', $recsensorial[0]->recsensorial_rfc);
        $plantillaword->setValue('empresa_codigopostal', $recsensorial[0]->recsensorial_codigopostal);
        $plantillaword->setValue('empresa_representantelegal', $recsensorial[0]->recsensorial_representantelegal);
        $plantillaword->setValue('empresa_representanteseguridad', $recsensorial[0]->recsensorial_representanteseguridad);
        $plantillaword->setValue('actividad_principal', $recsensorial[0]->recsensorial_actividadprincipal);
        $plantillaword->setValue('direccion', $recsensorial[0]->recsensorial_direccion);
        $plantillaword->setValue('coordenadas', $recsensorial[0]->recsensorial_coordenadas);


        // $plantillaword->setValue('descripción_proceso', str_replace("\n", "<w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace(['\\', '/', ':', '*', '&', '?', '<', '>', '|'], '-', $recsensorial[0]->recsensorial_descripcionproceso))));
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


        $plantillaword->setValue('observación_horarios', $recsensorial[0]->recsensorial_obscategorias);
        $plantillaword->setValue('fecha_elaboracion', $recsensorial[0]->fecha_elaboracion);
        $plantillaword->setValue('folio', $recsensorial[0]->recsensorial_foliofisico);
        $plantillaword->setValue('fecha', $recsensorial[0]->fechainicio_texto . ' al ' . $recsensorial[0]->fechafin_texto);
        $plantillaword->setValue('elabora', $recsensorial[0]->recsensorial_elabora1);


        // Imagen FOTO
        if ($recsensorial[0]->recsensorial_fotoubicacion) {
            if (file_exists(storage_path('app/' . $recsensorial[0]->recsensorial_fotoubicacion))) {
                $plantillaword->setImageValue('mapafoto', array('path' => storage_path('app/' . $recsensorial[0]->recsensorial_fotoubicacion), 'height' => 650, 'width' => 650, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('mapafoto', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }
        } else {
            $plantillaword->setValue('mapafoto', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
        }


        // DISEÑO TABLAS
        //================================================================================

        // formato celdas
        $encabezado_celda = array('bgColor' => '1A5276', 'valign' => 'center', 'cellMargin' => 100); //'bgColor' => '1A5276'
        $encabezado_texto = array('color' => 'FFFFFF', 'size' => 11, 'bold' => false, 'name' => 'Arial');
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


        // TABLA METODOLOGIA
        //================================================================================


        // Crear tabla
        $table = null;
        $lista_agentes = "";
        $table = new Table(array('name' => 'Arial', 'width' => 8550, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                recsensorialpruebas.recsensorial_id,
                                recsensorialpruebas.catprueba_id,
                                cat_prueba.catPrueba_Nombre
                            FROM
                                recsensorialpruebas
                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id 
                            WHERE
                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialpruebas.catprueba_id ASC');


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Agente/factor', $encabezado_texto);
        $table->addCell(7000, $encabezado_celda)->addTextRun($centrado)->addText('Normas y/o procedimientos', $encabezado_texto);


        // registros tabla
        foreach ($sql as $key => $value) {
            $lista_normas = "";
            $normas = DB::select('SELECT
                                        cat_pruebanorma.catpruebanorma_numero,
                                        cat_pruebanorma.catpruebanorma_descripcion
                                    FROM
                                        cat_pruebanorma
                                    WHERE
                                        cat_pruebanorma.cat_prueba_id = ' . $value->catprueba_id . '
                                    ORDER BY
                                     catpruebanorma_tipo ASC');

            // Formatear lineas
            foreach ($normas as $key2 => $norma) {
                if ($key2 === (count($normas) - 1)) //La ultima linea, quitar BR
                {
                    $lista_normas .= $norma->catpruebanorma_numero . ": " . $norma->catpruebanorma_descripcion;
                } else {
                    $lista_normas .= $norma->catpruebanorma_numero . ": " . $norma->catpruebanorma_descripcion . "<w:br/><w:br/>";
                }
            }

            $table->addRow(); //fila
            $table->addCell(3000, $celda)->addTextRun($centrado)->addText($value->catPrueba_Nombre, $textonegrita);
            $table->addCell(7000, $celda)->addTextRun($justificado)->addText($lista_normas, $texto);
        }


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_metodologia', $table);


        // TABLA AREAS
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                recsensorialarea.recsensorial_id,
                                recsensorialarea.id,
                                IFNULL( recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                IFNULL((
                                    SELECT
                                        CONCAT("● ", REPLACE(GROUP_CONCAT(cat_prueba.catPrueba_Nombre), ",", "<w:br />● "))
                                    FROM
                                        recsensorialareapruebas
                                        RIGHT JOIN cat_prueba ON recsensorialareapruebas.catprueba_id = cat_prueba.id
                                    WHERE
                                        recsensorialareapruebas.recsensorialarea_id = recsensorialarea.id
                                ), "Sin dato") AS agetes,
                                IFNULL((
                                    SELECT
                                        CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● ")) 
                                    FROM
                                        recsensorialareacategorias
                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                    WHERE
                                        recsensorialareacategorias.recsensorialarea_id = recsensorialarea.id
                                ), "Sin dato") AS categorias
                            FROM
                                recsensorialarea 
                            WHERE
                                recsensorialarea.recsensorial_id = ' . $recsensorial_id . '
                            ORDER BY
                                recsensorialarea.id ASC');

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(500, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
        $table->addCell(3150, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
        $table->addCell(3150, $encabezado_celda)->addTextRun($centrado)->addText('Agente o factor de riesgo', $encabezado_texto);
        $table->addCell(3150, $encabezado_celda)->addTextRun($centrado)->addText('Categoría expuesta', $encabezado_texto);


        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($No, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->agetes, $texto);
            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->categorias, $texto);
            $No += 1;
        }


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_areas', $table);


        // TABLA CATEGORIAS
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                TABLA.recsensorialcategoria_id,
                                TABLA.recsensorialcategoria_nombrecategoria,
                                TABLA.catmovilfijo_nombre,
                                SUM(TABLA.recsensorialareacategorias_total) AS total
                            FROM
                                (
                                    SELECT
                                        recsensorialareacategorias.recsensorialcategoria_id,
                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                        IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                        catmovilfijo.catmovilfijo_nombre,
                                        recsensorialareacategorias.recsensorialareacategorias_total
                                    FROM
                                        recsensorialarea
                                        INNER JOIN recsensorialareacategorias ON recsensorialarea.id = recsensorialareacategorias.recsensorialarea_id
                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                        LEFT JOIN catmovilfijo ON recsensorialcategoria.catmovilfijo_id = catmovilfijo.id 
                                    WHERE
                                        recsensorialarea.recsensorial_id = ' . $recsensorial_id . '
                                    GROUP BY
                                        recsensorialareacategorias.recsensorialcategoria_id,
                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                        catmovilfijo.catmovilfijo_nombre,
                                        recsensorialareacategorias.recsensorialareacategorias_total
                                ) AS TABLA
                            GROUP BY
                                TABLA.recsensorialcategoria_id,
                                TABLA.recsensorialcategoria_nombrecategoria,
                                TABLA.catmovilfijo_nombre
                            ORDER BY
                                TABLA.recsensorialcategoria_id ASC');


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(500, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
        $table->addCell(5500, $encabezado_celda)->addTextRun($centrado)->addText('Puesto o categoría', $encabezado_texto);
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Tipo', $encabezado_texto);
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores', $encabezado_texto);


        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($No, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catmovilfijo_nombre, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->total, $texto);

            $total += ($value->total + 0);
            $No += 1;
        }


        $table->addRow(); //fila
        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de trabajadores en la instalación', $textototal); // combina columna
        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total, $textonegrita);


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_categorias', $table);


        // TABLA HORARIOS
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                recsensorialcategoria.id,
                                recsensorialcategoria.recsensorial_id,
                                IFNULL(catdepartamento.catdepartamento_nombre, "Sin dato" ) AS catdepartamento_nombre,
                                IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                recsensorialcategoria.recsensorialcategoria_horasjornada,
                                TIME_FORMAT(recsensorialcategoria.recsensorialcategoria_horarioentrada, "%H:%i") AS recsensorialcategoria_horarioentrada,
                                TIME_FORMAT(recsensorialcategoria.recsensorialcategoria_horariosalida, "%H:%i") AS recsensorialcategoria_horariosalida 
                            FROM
                                recsensorialcategoria
                                LEFT JOIN catdepartamento ON recsensorialcategoria.catdepartamento_id = catdepartamento.id 
                            WHERE
                                recsensorialcategoria.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                catdepartamento.catdepartamento_nombre ASC,
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Departamento de adscripción', $encabezado_texto);
        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Jornada (horas)', $encabezado_texto);
        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Horario de jornada', $encabezado_texto);

        // registros tabla
        $departamento = 'xxx';
        $horarios = 'xxx';
        foreach ($sql as $key => $value) {
            if ($value->recsensorialcategoria_horasjornada == 24) {
                $horarios = 'COMPLETO';
            } else {
                $horarios = $value->recsensorialcategoria_horarioentrada . ' - ' . $value->recsensorialcategoria_horariosalida;
            }

            if ($departamento != $value->catdepartamento_nombre) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->catdepartamento_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_horasjornada, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($horarios, $texto);

                $departamento = $value->catdepartamento_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_horasjornada, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($horarios, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_horarios', $table);


        // TABLA MAQUINARIA
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        $sql = DB::select('SELECT
                                recsensorialmaquinaria.id,
                                recsensorialmaquinaria.recsensorial_id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                IFNULL(recsensorialmaquinaria.recsensorialmaquinaria_nombre, "Sin dato" ) AS recsensorialmaquinaria_nombre,
                                recsensorialmaquinaria.recsensorialmaquinaria_cantidad 
                            FROM
                                recsensorialmaquinaria
                                LEFT JOIN recsensorialarea ON recsensorialmaquinaria.recsensorialarea_id = recsensorialarea.id 
                            WHERE
                                recsensorialmaquinaria.recsensorial_id = ' . $recsensorial_id . '
                            ORDER BY
                                recsensorialarea.id ASC,
                                recsensorialmaquinaria.recsensorialmaquinaria_nombre ASC');

        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
        $table->addCell(5500, $encabezado_celda)->addTextRun($centrado)->addText('Fuentes generadoras', $encabezado_texto);
        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);


        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell(null, $continua_fila);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_maquinaria', $table);


        // TABLA EQUIPO DE PROTECCION PERSONAL
        //================================================================================


        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                recsensorialequipopp.recsensorial_id,
                                IFNULL(IF( recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", CONCAT( recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_funcioncategoria, ")" ) ), "Sin dato") AS categoria,
                                recsensorialequipopp.catpartecuerpo_id,
                                catpartecuerpo.catpartecuerpo_nombre,
                                recsensorialequipopp.recsensorialequipopp_descripcion 
                            FROM
                                recsensorialequipopp
                                LEFT JOIN recsensorialcategoria ON recsensorialequipopp.recsensorialcategoria_id = recsensorialcategoria.id
                                LEFT JOIN catpartecuerpo ON recsensorialequipopp.catpartecuerpo_id = catpartecuerpo.id 
                            WHERE
                                recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC,
                                recsensorialequipopp.catpartecuerpo_id ASC');


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


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_epp', $table);



        // TABLAS AGENTES EVALUADOS
        //================================================================================


        $sql = DB::select('SELECT
                                cat_prueba.id,
                                cat_prueba.catPrueba_Nombre,
                                IFNULL((
                                    SELECT
                                        IF(recsensorialpruebas.catprueba_id, 1, 0)
                                    FROM
                                        recsensorialpruebas 
                                    WHERE
                                        recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' AND recsensorialpruebas.catprueba_id = cat_prueba.id
                                ), 0) AS encontrato
                            FROM
                                cat_prueba
                            ORDER BY
                                cat_prueba.id ASC');


        foreach ($sql as $key => $valuex) {
            if ($valuex->encontrato == 0) {
                $plantillaword->setValue('titulo_agente_' . $valuex->id, '');
                $plantillaword->setValue('tabla_agente_' . $valuex->id, '');
                $plantillaword->setValue('observacion_agente_' . $valuex->id, '');

                if ($valuex->id == 1) {
                    $plantillaword->setValue('titulo_agente_1_1', '');
                    $plantillaword->setValue('titulo_agente_1_2', '');
                    $plantillaword->setValue('tabla_agente_1_2', '');
                }


                if ($valuex->id == 13) {
                    $plantillaword->setValue('observacion1_agente_13', '');
                    $plantillaword->setValue('observacion2_agente_13', '');
                }
            } else {
                switch ($valuex->id) {
                    case 1:
                        // Crear tabla 1
                        $table = null;
                        $No = 1;
                        $total = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        // $sql = DB::select('SELECT
                        //                         parametroruidosonometria.id,
                        //                         parametroruidosonometria.recsensorial_id,
                        //                         parametroruidosonometria.recsensorialarea_id,
                        //                         IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                        //                         parametroruidosonometria.parametroruidosonometria_puntos,
                        //                         IFNULL((
                        //                             SELECT
                        //                                 CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                        //                             FROM
                        //                                 parametroruidosonometriacategorias
                        //                                 LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                        //                             WHERE
                        //                                 parametroruidosonometriacategorias.recsensorialarea_id = parametroruidosonometria.recsensorialarea_id
                        //                         ), "-") AS categorias
                        //                     FROM
                        //                         parametroruidosonometria
                        //                         LEFT JOIN recsensorialarea ON parametroruidosonometria.recsensorialarea_id = recsensorialarea.id
                        //                     WHERE
                        //                         parametroruidosonometria.recsensorial_id = '.$recsensorial_id.' 
                        //                     ORDER BY
                        //                         recsensorialarea.id ASC');


                        $sql = DB::select('SELECT
                                                    tabla.recsensorial_id,
                                                    tabla.recsensorialarea_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                                                        FROM
                                                            parametroruidosonometriacategorias
                                                            LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                        WHERE
                                                            parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                    ), "-") AS categorias,
                                                    tabla.parametroruidosonometria_puntos,
                                                    MIN(tabla.medicion) AS min,
                                                    MAX(tabla.medicion) AS max,
                                                    (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                    (
                                                        CASE
                                                            WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                            WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                            ELSE 0
                                                        END
                                                    ) AS aplicaevaluacion,
                                                    (
                                                        CASE
                                                            WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN "Inestable"
                                                            ELSE "Estable"
                                                        END
                                                    ) AS resultado
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_medmax AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_medmin AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med1 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                parametroruidosonometria.parametroruidosonometria_med2 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med3 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med4 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med5 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med6 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med7 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med8 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med9 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                parametroruidosonometria.parametroruidosonometria_med10 AS medicion
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                    ) AS tabla
                                                    LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    tabla.recsensorial_id = ' . $recsensorial_id . ' 
                                                GROUP BY
                                                    tabla.recsensorial_id,
                                                    tabla.recsensorialarea_id,
                                                    parametroruidosonometria_puntos
                                                ORDER BY
                                                    tabla.recsensorialarea_id ASC');


                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. de puntos', $encabezado_texto);

                        // registros tabla
                        // $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->categorias, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroruidosonometria_puntos, $texto);

                            $total += $value->parametroruidosonometria_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total sonometrías', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setValue('titulo_agente_' . $valuex->id . '_1', 'Sonometrías');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);

                        //===================================================================

                        // Crear tabla 2
                        $table = null;
                        $No = 1;
                        $total = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroruidodosimetria.id,
                                                    parametroruidodosimetria.recsensorial_id,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroruidodosimetria.parametroruidodosimetria_dosis 
                                                FROM
                                                    parametroruidodosimetria
                                                    LEFT JOIN recsensorialcategoria ON parametroruidodosimetria.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroruidodosimetria.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(8000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de dosis', $encabezado_texto);

                        // registros tabla
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroruidodosimetria_dosis, $texto);

                            $total += $value->parametroruidodosimetria_dosis;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total dosimetrías', $textototal);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id . '_2', '<w:br />Dosimetrías');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id . '_2', $table);
                        break;
                    case 2:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrovibracion.id,
                                                    parametrovibracion.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametrovibracion.parametrovibracion_puntovce,
                                                    parametrovibracion.parametrovibracion_puntoves 
                                                FROM
                                                    parametrovibracion
                                                    LEFT JOIN recsensorialarea ON parametrovibracion.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametrovibracion.recsensorialcategoria_id = recsensorialcategoria.id 
                                                WHERE
                                                    parametrovibracion.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos VCE', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos VES', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntovce, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntoves, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntovce, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntoves, $texto);
                            }

                            $total1 += $value->parametrovibracion_puntovce;
                            $total2 += $value->parametrovibracion_puntoves;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total2, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />VCE: Vibraciones de cuerpo entero<w:br/>VES: Vibraciones en extremidades superiores');
                        break;
                    case 3:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrotemperatura.id,
                                                    parametrotemperatura.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametrotemperatura.parametrotemperatura_puntote,
                                                    parametrotemperatura.parametrotemperatura_puntota 
                                                FROM
                                                    parametrotemperatura
                                                    LEFT JOIN recsensorialarea ON parametrotemperatura.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametrotemperatura.recsensorialcategoria_id = recsensorialcategoria.id 
                                                WHERE
                                                    parametrotemperatura.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos TE', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos TA', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntote, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntota, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntote, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntota, $texto);
                            }

                            $total1 += $value->parametrotemperatura_puntote;
                            $total2 += $value->parametrotemperatura_puntota;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total2, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />TE: Temperaturas elevadas<w:br />TA: Temperaturas abatidas');
                        break;
                    case 4:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroiluminacion.recsensorial_id,
                                                    parametroiluminacion.id,
                                                    parametroiluminacion.recsensorialarea_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    parametroiluminacion.parametroiluminacion_puntos
                                                FROM
                                                    parametroiluminacion
                                                    LEFT JOIN recsensorialarea ON parametroiluminacion.recsensorialarea_id = recsensorialarea.id 
                                                WHERE
                                                    parametroiluminacion.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        // $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            $lista_categrias = '';
                            $sql2 = DB::select('SELECT
                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria
                                                    FROM
                                                        parametroiluminacioncategorias
                                                        LEFT JOIN recsensorialcategoria ON parametroiluminacioncategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                    WHERE
                                                        parametroiluminacioncategorias.recsensorialarea_id = ' . $value->recsensorialarea_id . '
                                                    ORDER BY
                                                        recsensorialcategoria.id ASC');

                            foreach ($sql2 as $key2 => $value2) {
                                if ($key2 != (count($sql2) - 1)) {
                                    $lista_categrias .= '● ' . $value2->recsensorialcategoria_nombrecategoria . '<w:br />';
                                } else {
                                    $lista_categrias .= '● ' . $value2->recsensorialcategoria_nombrecategoria;
                                }
                            }

                            $table->addRow(); //fila
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($lista_categrias, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroiluminacion_puntos, $texto);

                            $total1 += $value->parametroiluminacion_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 5:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroradiacionionizante.id,
                                                    parametroradiacionionizante.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroradiacionionizante.parametroradiacionionizante_fuente,
                                                    parametroradiacionionizante.parametroradiacionionizante_puntos 
                                                FROM
                                                    parametroradiacionionizante
                                                    LEFT JOIN recsensorialarea ON parametroradiacionionizante.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroradiacionionizante.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroradiacionionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de fuente', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_puntos, $texto);
                            }

                            $total1 += $value->parametroradiacionionizante_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 6:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroradiacionnoionizante.id,
                                                    parametroradiacionnoionizante.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroradiacionnoionizante.parametroradiacionnoionizante_fuente,
                                                    parametroradiacionnoionizante.parametroradiacionnoionizante_puntos 
                                                FROM
                                                    parametroradiacionnoionizante
                                                    LEFT JOIN recsensorialarea ON parametroradiacionnoionizante.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroradiacionnoionizante.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroradiacionnoionizante.recsensorial_id = ' . $recsensorial_id . '  
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de fuente', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_puntos, $texto);
                            }

                            $total1 += $value->parametroradiacionnoionizante_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 7:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroprecionesambientales.id,
                                                    parametroprecionesambientales.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroprecionesambientales.parametroprecionesambientales_contaminante,
                                                    parametroprecionesambientales.parametroprecionesambientales_puntos 
                                                FROM
                                                    parametroprecionesambientales
                                                    LEFT JOIN recsensorialarea ON parametroprecionesambientales.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroprecionesambientales.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroprecionesambientales.recsensorial_id = ' . $recsensorial_id . '  
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Contaminante', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_contaminante, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_contaminante, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_puntos, $texto);
                            }

                            $total1 += $value->parametroprecionesambientales_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 8:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrocalidadaire.id,
                                                    parametrocalidadaire.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrocalidadaire.parametrocalidadaire_ubicacion,
                                                    (
                                                        SELECT
                                                            CONCAT("● ", IFNULL(REPLACE(GROUP_CONCAT(REPLACE(catparametrocalidadairecaracteristica.catparametrocalidadairecaracteristica_caracteristica, ",", "ˏ")), ",", "<w:br/>● "), "Sin dato")) AS caracteristicas 
                                                        FROM
                                                            parametrocalidadairecaracteristica
                                                            LEFT JOIN catparametrocalidadairecaracteristica ON parametrocalidadairecaracteristica.catparametrocalidadairecaracteristica_id = catparametrocalidadairecaracteristica.id
                                                        WHERE
                                                            parametrocalidadairecaracteristica.parametrocalidadaire_id = parametrocalidadaire.id
                                                    ) AS caracteristicas,
                                                    parametrocalidadaire.parametrocalidadaire_puntos 
                                                FROM
                                                    parametrocalidadaire
                                                    LEFT JOIN recsensorialarea ON parametrocalidadaire.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    parametrocalidadaire.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrocalidadaire.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3300, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(5200, $encabezado_celda)->addTextRun($centrado)->addText('características a medir', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(3500, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(3300, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_ubicacion, $texto);
                                $table->addCell(5200, $celda)->addTextRun($izquierda)->addText(str_replace('ˏ', ',', $value->caracteristicas), $texto);
                                $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(3500, $continua_fila);
                                $table->addCell(3300, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_ubicacion, $texto);
                                $table->addCell(5200, $celda)->addTextRun($izquierda)->addText(str_replace('ˏ', ',', $value->caracteristicas), $texto);
                                $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_puntos, $texto);
                            }

                            $total1 += $value->parametrocalidadaire_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(12000, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(1500, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />Cada punto de CAI incluye los siguientes parámetros: temperatura de confort, velocidad y caudal del aire, humedad relativa, monóxido de carbono (CO), dióxido de carbono (CO2) y bioaerosoles (bacterias, hongos y actinomicetos).');
                        break;
                    case 9:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroagua.id,
                                                    parametroagua.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    parametroagua.parametroagua_ubicacion,
                                                    parametroagua.parametroagua_tipouso,
                                                    catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                    parametroagua.parametroagua_puntos,
                                                    IFNULL((
                                                        SELECT  
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica), ",", "<w:br />● "))
                                                        FROM
                                                            parametroaguacaracteristica
                                                            RIGHT JOIN catparametroaguacaracteristica ON parametroaguacaracteristica.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                                        WHERE
                                                            parametroaguacaracteristica.parametroagua_id = parametroagua.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametroagua
                                                    LEFT JOIN recsensorialarea ON parametroagua.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                                WHERE
                                                    parametroagua.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametroagua.parametroagua_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo uso', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_tipouso, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_tipouso, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_puntos, $texto);
                            }

                            $total1 += $value->parametroagua_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 10:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrohielo.id,
                                                    parametrohielo.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrohielo.parametrohielo_ubicacion,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    parametrohielo.parametrohielo_puntos,
                                                    IFNULL((
                                                        SELECT
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica), ",", "<w:br />● "))
                                                        FROM
                                                            parametrohielocaracteristica
                                                            RIGHT JOIN catparametrohielocaracteristica ON parametrohielocaracteristica.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                        WHERE
                                                            parametrohielocaracteristica.parametrohielo_id = parametrohielo.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametrohielo
                                                    LEFT JOIN recsensorialarea ON parametrohielo.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                WHERE
                                                    parametrohielo.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrohielo.parametrohielo_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametrohielocaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametrohielocaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_puntos, $texto);
                            }

                            $total1 += $value->parametrohielo_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 11:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroalimento.id,
                                                    parametroalimento.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametroalimento.parametroalimento_ubicacion,
                                                    parametroalimento.parametroalimento_puntos,
                                                    IFNULL((
                                                            SELECT
                                                                CONCAT("● ", REPLACE(GROUP_CONCAT(catparametroalimentocaracteristica.catparametroalimentocaracteristica_caracteristica), ",", "<w:br />● "))    
                                                            FROM
                                                                parametroalimentocaracteristica
                                                                RIGHT JOIN catparametroalimentocaracteristica ON parametroalimentocaracteristica.catparametroalimentocaracteristica_id = catparametroalimentocaracteristica.id
                                                            WHERE
                                                                parametroalimentocaracteristica.parametroalimento_id = parametroalimento.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametroalimento
                                                    LEFT JOIN recsensorialarea ON parametroalimento.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    parametroalimento.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametroalimento.parametroalimento_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_puntos, $texto);
                            }

                            $total1 += $value->parametroalimento_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 12:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrosuperficie.id,
                                                    parametrosuperficie.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrosuperficie.parametrosuperficie_ubicacion,
                                                    parametrosuperficie.parametrosuperficie_caracteristica,
                                                    parametrosuperficie.parametrosuperficie_observacion,
                                                    parametrosuperficie.parametrosuperficie_puntos,
                                                    IFNULL((
                                                            SELECT
                                                                CONCAT("● ", REPLACE(GROUP_CONCAT(catparametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_caracteristica), ",", "<w:br />● "))    
                                                            FROM
                                                                parametrosuperficiecaracteristica
                                                                RIGHT JOIN catparametrosuperficiecaracteristica ON parametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_id = catparametrosuperficiecaracteristica.id
                                                            WHERE
                                                                parametrosuperficiecaracteristica.parametrosuperficie_id = parametrosuperficie.id
                                                    ), "Sin dato") AS analisis 
                                                FROM
                                                    parametrosuperficie
                                                    LEFT JOIN recsensorialarea ON parametrosuperficie.recsensorialarea_id = recsensorialarea.id 
                                                WHERE
                                                    parametrosuperficie.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrosuperficie.parametrosuperficie_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_caracteristica, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_caracteristica, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_puntos, $texto);
                            }

                            $total1 += $value->parametrosuperficie_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 13:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroergonomia.id,
                                                    parametroergonomia.recsensorial_id,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    IF(parametroergonomia.parametroergonomia_movimientorepetitivo = 1, "Sí", "-") AS parametroergonomia_movimientorepetitivo,
                                                    IF(parametroergonomia.parametroergonomia_posturamantenida = 1, "Sí", "-") AS parametroergonomia_posturamantenida,
                                                    IF(parametroergonomia.parametroergonomia_posturaforzada = 1, "Sí", "-") AS parametroergonomia_posturaforzada,
                                                    IF(parametroergonomia.parametroergonomia_cargamanual = 1, "Sí", "-") AS parametroergonomia_cargamanual,
                                                    IF(parametroergonomia.parametroergonomia_fuerza = 1, "Sí", "-") AS parametroergonomia_fuerza,
                                                    IFNULL(IF((parametroergonomia.parametroergonomia_movimientorepetitivo +
                                                    parametroergonomia.parametroergonomia_posturamantenida +
                                                    parametroergonomia.parametroergonomia_posturaforzada +
                                                    parametroergonomia.parametroergonomia_cargamanual +
                                                    parametroergonomia.parametroergonomia_fuerza) >= 2, 1, 0), 0) AS puntos,
                                                    IFNULL((
                                                        SELECT
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato")), ",", "<w:br />● "))
                                                        FROM
                                                            parametroergonomiaarea
                                                            LEFT JOIN recsensorialarea ON parametroergonomiaarea.recsensorialarea_id = recsensorialarea.id 
                                                        WHERE
                                                            parametroergonomiaarea.parametroergonomia_id = parametroergonomia.id
                                                    ), "Sin dato") AS areas
                                                FROM
                                                    parametroergonomia
                                                    LEFT JOIN recsensorialcategoria ON parametroergonomia.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroergonomia.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Movimi. repetitivo', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Postura mantenida', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Postura forzada', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Carga manual', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Fuerza', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $categoria = 'xxx';
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->areas, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_movimientorepetitivo, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_posturamantenida, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_posturaforzada, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_cargamanual, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_fuerza, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->puntos, $texto);

                            $total1 += $value->puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de categorías', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setValue('observacion1_agente_' . $valuex->id, 'Para el presente recorrido sensorial se deben tener en cuenta las consideraciones estipuladas en la NOM-036-STPS-2018 y procedimiento para la evaluación e identificación de los factores de Riesgo Ergonómicos: PO-SO-TC-004-2015.<w:br />');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion2_agente_' . $valuex->id, '<w:br />Nota: Identifique con una marca según corresponda la presentación de cada uno de los aspectos derivados de la carga física en cada categoría de acuerdo con el recorrido sensorial realizado.');
                        break;
                    case 14:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametropsicosocial.id,
                                                    parametropsicosocial.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametropsicosocial.parametropsicosocial_nopersonas 
                                                FROM
                                                    parametropsicosocial
                                                    LEFT JOIN recsensorialarea ON parametropsicosocial.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametropsicosocial.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametropsicosocial.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. personas', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametropsicosocial_nopersonas, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametropsicosocial_nopersonas, $texto);
                            }

                            $total1 += $value->parametropsicosocial_nopersonas;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total evaluaciones', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 15:
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        // $plantillaword->setValue('tabla_agente_'.$valuex->id, '');
                        // $plantillaword->setComplexBlock('tabla_agente_'.$valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, 'El reconocimiento de agentes químicos realizado y/o validado por un laboratorio de pruebas acreditado y aprobado se encuentra en el informe de Reconocimiento de agentes químicos en el ambiente laboral.');
                        break;
                    case 16:
                        // Consulta información
                        $total_serviciopersonal = DB::select('SELECT
                                                                        COUNT(parametroserviciopersonal.id) AS totalregistros,
                                                                        IFNULL(SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ), 0) AS totalpuntos 
                                                                    FROM
                                                                        parametroserviciopersonal
                                                                    WHERE
                                                                        parametroserviciopersonal.recsensorial_id = ' . $recsensorial_id);

                        if (($total_serviciopersonal[0]->totalpuntos + 0) > 1) {
                            // Mostrar datos en word
                            $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                            $plantillaword->setValue('tabla_agente_' . $valuex->id, 'Se realizará un estudio que incluya la evaluación de la infraestructura para servicios del personal contabilizando a un total de ' . $total_serviciopersonal[0]->totalpuntos . ' trabajadores por el total de la instalación.');
                        } else {
                            // Mostrar datos en word
                            $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                            $plantillaword->setValue('tabla_agente_' . $valuex->id, 'No se considerará la evaluación de Infraestructura para servicios al personal, debido a que en la instalación solamente se encuentra un trabajador por turno de trabajo.');
                        }

                        break;
                    case 17:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    TABLA.recsensorial_id,
                                                    TABLA.parametromapariesgo_tipo,
                                                    TABLA.cantidad
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                parametromapariesgo.recsensorial_id,
                                                                "Mapa de riesgos (Tipo 1)" AS parametromapariesgo_tipo,
                                                                SUM(parametromapariesgo.parametromapariesgo_tipo1) AS cantidad
                                                            FROM
                                                                parametromapariesgo
                                                            WHERE
                                                                parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                            GROUP BY
                                                                parametromapariesgo.recsensorial_id
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametromapariesgo.recsensorial_id,
                                                                "Mapa de riesgos (Tipo 2)" AS parametromapariesgo_tipo,
                                                                SUM(parametromapariesgo.parametromapariesgo_tipo2) AS cantidad
                                                            FROM
                                                                parametromapariesgo
                                                            WHERE
                                                                parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                            GROUP BY
                                                                parametromapariesgo.recsensorial_id
                                                        )
                                                    ) AS TABLA
                                                WHERE
                                                    TABLA.cantidad > 0
                                                ORDER BY
                                                    TABLA.parametromapariesgo_tipo ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(11500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);

                        // registros tabla
                        $total = 0;
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(11500, $celda)->addTextRun($centrado)->addText($value->parametromapariesgo_tipo, $texto);
                            $table->addCell(2000, $celda)->addTextRun($centrado)->addText($value->cantidad, $texto);

                            $total += ($value->cantidad + 0);
                        }

                        $table->addRow(); //fila
                        $table->addCell(11500, array('gridSpan' => 1, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total mapas de riesgos', $textototal); // combina columna
                        $table->addCell(2000, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    default:
                        # Code...
                        break;
                }
            }
        }


        // TABLA RESUMEN
        //================================================================================

        // Crear tabla
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => 'Arial', 'width' => 9950, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                recsensorialpruebas.recsensorial_id,
                                cat_prueba.catPrueba_Tipo,
                                TABLA1.catprueba_id,
                                IF(IFNULL(TABLA1.agente_subnombre, "") = "", cat_prueba.catPrueba_Nombre, CONCAT(cat_prueba.catPrueba_Nombre, " (", TABLA1.agente_subnombre, ")")) AS catPrueba_Nombre,
                                cat_prueba.catPrueba_Nombre AS agente_nombre,
                                TABLA1.agente_subnombre AS agente_subnombre,
                                TABLA1.totalregistros,
                                TABLA1.totalpuntos,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                ELSE "Extra chica"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                ELSE "N/A"
                                            END
                                        )
                                        ,
                                            "N/A"
                                    )
                                ) AS tipoinstalacion,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "0"
                                                WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                ELSE "XC"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "0"
                                                WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                ELSE "N/A"
                                            END
                                        )
                                        ,
                                            "N/A"
                                    )
                                ) AS tipoinstalacion_abreviacion,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                ELSE "#0080FF"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                ELSE "#999999"
                                            END
                                        )
                                        ,
                                        "#999999"
                                    )
                                ) AS tipoinstalacion_color
                            FROM
                                (
                                    (
                                        SELECT
                                            TABLARUIDO.catprueba_id,
                                            NULL AS agente_subnombre,
                                            SUM(TABLARUIDO.totalregistros) AS totalregistros,
                                            SUM(TABLARUIDO.totalpuntos) AS totalpuntos
                                        FROM
                                            (
                                                (
                                                    -- SELECT
                                                    --     1 AS catprueba_id,
                                                    --     NULL AS agente_subnombre,
                                                    --     COUNT(parametroruidosonometria.recsensorial_id) AS totalregistros,
                                                    --     SUM(parametroruidosonometria.parametroruidosonometria_puntos) AS totalpuntos 
                                                    -- FROM
                                                    --     parametroruidosonometria
                                                    -- WHERE
                                                    --     parametroruidosonometria.recsensorial_id = 182
                                                    -- GROUP BY
                                                    --     parametroruidosonometria.recsensorial_id,
                                                    --     parametroruidosonometria.parametroruidosonometria_puntos


                                                    SELECT
                                                        1 AS catprueba_id,
                                                        NULL AS agente_subnombre,
                                                        COUNT(tablaresumen.recsensorialarea_id) AS totalregistros,
                                                        SUM(tablaresumen.puntos) AS totalpuntos
                                                    FROM
                                                        (
                                                            SELECT
                                                                tabla.recsensorial_id,
                                                                tabla.recsensorialarea_id,
                                                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                                IFNULL((
                                                                    SELECT
                                                                        CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                                                                    FROM
                                                                        parametroruidosonometriacategorias
                                                                        LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                                    WHERE
                                                                        parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                                ), "-") AS categorias,
                                                                tabla.parametroruidosonometria_puntos AS puntos,
                                                                MIN(tabla.medicion) AS min,
                                                                MAX(tabla.medicion) AS max,
                                                                (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                                (
                                                                    CASE
                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                                        ELSE 0
                                                                    END
                                                                ) AS aplicaevaluacion,
                                                                IFNULL((
                                                                    REPLACE(GROUP_CONCAT(tabla.medicion_texto ORDER BY tabla.medicion_texto ASC), ",", "<w:br/>")
                                                                ), "NP") AS mediciones,
                                                                (
                                                                    CASE
                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN "Se evalua"
                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 
                                                                            CASE
                                                                                WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN (CONCAT("Se evalua<w:br/>Ruido inestable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                ELSE (CONCAT("Se evalua<w:br/>Ruido Estable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                            END
                                                                        ELSE "No se evalua<w:br/>≤80 dB"
                                                                    END
                                                                ) AS resultado
                                                            FROM
                                                                (
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmax) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, CONCAT("Max: ", parametroruidosonometria.parametroruidosonometria_medmax, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmin) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, CONCAT("Min: ", parametroruidosonometria.parametroruidosonometria_medmin, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,                       
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med1) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, CONCAT("Med01: ", parametroruidosonometria.parametroruidosonometria_med1, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med2) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, CONCAT("Med02: ", parametroruidosonometria.parametroruidosonometria_med2, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med3) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, CONCAT("Med03: ", parametroruidosonometria.parametroruidosonometria_med3, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med4) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, CONCAT("Med04: ", parametroruidosonometria.parametroruidosonometria_med4, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med5) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, CONCAT("Med05: ", parametroruidosonometria.parametroruidosonometria_med5, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med6) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, CONCAT("Med06: ", parametroruidosonometria.parametroruidosonometria_med6, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med7) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, CONCAT("Med07: ", parametroruidosonometria.parametroruidosonometria_med7, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med8) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, CONCAT("Med08: ", parametroruidosonometria.parametroruidosonometria_med8, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med9) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, CONCAT("Med09: ", parametroruidosonometria.parametroruidosonometria_med9, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med10) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, CONCAT("Med10: ", parametroruidosonometria.parametroruidosonometria_med10, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                ) AS tabla
                                                                LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                            WHERE
                                                                tabla.recsensorial_id = ' . $recsensorial_id . ' 
                                                            GROUP BY
                                                                tabla.recsensorial_id,
                                                                tabla.recsensorialarea_id,
                                                                parametroruidosonometria_puntos
                                                            ORDER BY
                                                                tabla.recsensorialarea_id ASC
                                                        ) AS tablaresumen
                                                    WHERE
                                                        tablaresumen.aplicaevaluacion = 1
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        1 AS catprueba_id,
                                                        NULL AS agente_subnombre,
                                                        COUNT(parametroruidodosimetria.recsensorial_id) AS totalregistros,
                                                        SUM(parametroruidodosimetria.parametroruidodosimetria_dosis) AS totalpuntos 
                                                    FROM
                                                        parametroruidodosimetria
                                                    WHERE
                                                        parametroruidodosimetria.recsensorial_id = ' . $recsensorial_id . '
                                                    GROUP BY
                                                        parametroruidodosimetria.recsensorial_id,
                                                        parametroruidodosimetria.parametroruidodosimetria_dosis
                                                )
                                            ) AS TABLARUIDO
                                        GROUP BY
                                            TABLARUIDO.catprueba_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            2 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT(parametrovibracion.recsensorial_id) AS totalregistros,
                                            SUM(parametrovibracion.parametrovibracion_puntovce + parametrovibracion.parametrovibracion_puntoves) AS totalpuntos  
                                        FROM
                                            parametrovibracion
                                        WHERE
                                            parametrovibracion.recsensorial_id = ' . $recsensorial_id . '
                                        GROUP BY
                                            parametrovibracion.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            3 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrotemperatura.recsensorial_id ) AS totalregistros,
                                            SUM( parametrotemperatura.parametrotemperatura_puntote + parametrotemperatura.parametrotemperatura_puntota ) AS totalpuntos 
                                        FROM
                                            parametrotemperatura 
                                        WHERE
                                            parametrotemperatura.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrotemperatura.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            4 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroiluminacion.recsensorial_id ) AS totalregistros,
                                            SUM( parametroiluminacion.parametroiluminacion_puntos ) AS totalpuntos 
                                        FROM
                                            parametroiluminacion 
                                        WHERE
                                            parametroiluminacion.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroiluminacion.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            5 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroradiacionionizante.recsensorial_id ) AS totalregistros,
                                            SUM( parametroradiacionionizante.parametroradiacionionizante_puntos ) AS totalpuntos 
                                        FROM
                                            parametroradiacionionizante 
                                        WHERE
                                            parametroradiacionionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroradiacionionizante.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            6 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroradiacionnoionizante.recsensorial_id ) AS totalregistros,
                                            SUM( parametroradiacionnoionizante.parametroradiacionnoionizante_puntos ) AS totalpuntos 
                                        FROM
                                            parametroradiacionnoionizante 
                                        WHERE
                                            parametroradiacionnoionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroradiacionnoionizante.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            7 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroprecionesambientales.recsensorial_id ) AS totalregistros,
                                            SUM( parametroprecionesambientales.parametroprecionesambientales_puntos ) AS totalpuntos 
                                        FROM
                                            parametroprecionesambientales 
                                        WHERE
                                            parametroprecionesambientales.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroprecionesambientales.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            8 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrocalidadaire.recsensorial_id ) AS totalregistros,
                                            SUM( parametrocalidadaire.parametrocalidadaire_puntos ) AS totalpuntos 
                                        FROM
                                            parametrocalidadaire 
                                        WHERE
                                            parametrocalidadaire.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrocalidadaire.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            9 AS catprueba_id,
                                            "Fisicoquímico" AS agente_subnombre,
                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                        FROM
                                            parametroagua
                                            INNER JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Fisicoquímico"
                                        GROUP BY
                                            parametroagua.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            9 AS catprueba_id,
                                            "Microbiológico" AS agente_subnombre,
                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                        FROM
                                            parametroagua
                                            LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Microbiológico"
                                        GROUP BY
                                            parametroagua.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            10 AS catprueba_id,
                                            "Fisicoquímico" AS agente_subnombre,
                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                        FROM
                                            parametrohielo
                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                        GROUP BY
                                            parametrohielo.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            10 AS catprueba_id,
                                            "Microbiológico" AS agente_subnombre,
                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                        FROM
                                            parametrohielo
                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Microbiológico"
                                        GROUP BY
                                            parametrohielo.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            11 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroalimento.recsensorial_id ) AS totalregistros,
                                            SUM( parametroalimento.parametroalimento_puntos ) AS totalpuntos 
                                        FROM
                                            parametroalimento 
                                        WHERE
                                            parametroalimento.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroalimento.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            12 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrosuperficie.recsensorial_id ) AS totalregistros,
                                            SUM( parametrosuperficie.parametrosuperficie_puntos ) AS totalpuntos 
                                        FROM
                                            parametrosuperficie 
                                        WHERE
                                            parametrosuperficie.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrosuperficie.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            13 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            TABLAERGONOMIA.recsensorial_id,
                                            SUM(TABLAERGONOMIA.totalpuntos) AS totalpuntos
                                        FROM
                                            (
                                                SELECT
                                                    parametroergonomia.recsensorial_id,
                                                    parametroergonomia.id,
                                                    IF((parametroergonomia.parametroergonomia_movimientorepetitivo + parametroergonomia.parametroergonomia_posturamantenida + parametroergonomia.parametroergonomia_posturaforzada + parametroergonomia.parametroergonomia_cargamanual +parametroergonomia.parametroergonomia_fuerza) > 1, 1, 0) AS totalpuntos
                                                FROM
                                                    parametroergonomia
                                                WHERE
                                                    parametroergonomia.recsensorial_id = ' . $recsensorial_id . '
                                                GROUP BY
                                                    parametroergonomia.recsensorial_id,
                                                    parametroergonomia.id,
                                                    parametroergonomia.parametroergonomia_movimientorepetitivo,
                                                    parametroergonomia.parametroergonomia_posturamantenida,
                                                    parametroergonomia.parametroergonomia_posturaforzada,
                                                    parametroergonomia.parametroergonomia_cargamanual,
                                                    parametroergonomia.parametroergonomia_fuerza
                                            ) AS TABLAERGONOMIA
                                        WHERE
                                            TABLAERGONOMIA.totalpuntos > 0
                                        GROUP BY
                                            TABLAERGONOMIA.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            14 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametropsicosocial.recsensorial_id ) AS totalregistros,
                                            SUM( parametropsicosocial.parametropsicosocial_nopersonas ) AS totalpuntos 
                                        FROM
                                            parametropsicosocial 
                                        WHERE
                                            parametropsicosocial.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametropsicosocial.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            TABLASERVICIOPERSONAL.catprueba_id,
                                            TABLASERVICIOPERSONAL.agente_subnombre,
                                            TABLASERVICIOPERSONAL.totalregistros,
                                            TABLASERVICIOPERSONAL.totalpuntos
                                        FROM
                                            (
                                                SELECT
                                                    16 AS catprueba_id,
                                                    NULL AS agente_subnombre,
                                                    COUNT( parametroserviciopersonal.recsensorial_id ) AS totalregistros,
                                                    SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ) AS totalpuntos 
                                                FROM
                                                    parametroserviciopersonal 
                                                WHERE
                                                    parametroserviciopersonal.recsensorial_id = ' . $recsensorial_id . ' 
                                                GROUP BY
                                                    parametroserviciopersonal.recsensorial_id
                                            ) AS TABLASERVICIOPERSONAL
                                        WHERE
                                            TABLASERVICIOPERSONAL.totalpuntos > 1
                                    )
                                    UNION ALL
                                      (
                                            SELECT
                                                  17 AS catprueba_id,
                                                  "Tipo 1" AS agente_subnombre,
                                                  COUNT(parametromapariesgo.parametromapariesgo_tipo1) AS totalregistros,
                                                  SUM(parametromapariesgo.parametromapariesgo_tipo1) AS totalpuntos
                                            FROM
                                                  parametromapariesgo
                                            WHERE
                                                  parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                            GROUP BY
                                                  parametromapariesgo.recsensorial_id
                                      )
                                      UNION ALL
                                      (
                                            SELECT
                                                  17 AS catprueba_id,
                                                  "Tipo 2" AS agente_subnombre,
                                                  COUNT(parametromapariesgo.parametromapariesgo_tipo2) AS totalregistros,
                                                  SUM(parametromapariesgo.parametromapariesgo_tipo2) AS totalpuntos
                                            FROM
                                                  parametromapariesgo
                                            WHERE
                                                  parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                            GROUP BY
                                                  parametromapariesgo.recsensorial_id
                                      )
                                ) TABLA1
                                LEFT JOIN recsensorialpruebas ON TABLA1.catprueba_id = recsensorialpruebas.catprueba_id
                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id
                            WHERE
                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' AND recsensorialpruebas.catprueba_id != 15
                            GROUP BY
                                recsensorialpruebas.recsensorial_id,
                                cat_prueba.catPrueba_Tipo,
                                TABLA1.catprueba_id,
                                cat_prueba.catPrueba_Nombre,
                                TABLA1.agente_subnombre,
                                TABLA1.totalregistros,
                                TABLA1.totalpuntos
                            ORDER BY
                                TABLA1.totalpuntos DESC,
                                cat_prueba.catPrueba_Nombre ASC');


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Agente / Factores de riesgo / Servicio', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Puntos de muestreos / Numero de personas / Cantidad', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de instalación', $encabezado_texto);


        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catPrueba_Nombre, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->totalpuntos, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->tipoinstalacion, $texto);
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('tabla_resumen_agentes', $table);



        // EJEMPLO DISEÑO TABLA CONBINADA
        //================================================================================

        /*
            // Crear tabla
            $table = new Table(array('width' => 9500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0,'unit' => TblWidth::TWIP));
            
            // encabezado
            $table->addRow(); //nueva fila
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Encabezado 1', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Encabezado 2', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Encabezado 3', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Encabezado 4', $encabezado_texto);

            // registros
            $table->addRow(); //nueva fila
            $table->addCell(2000, $combinar_fila)->addTextRun($centrado)->addText('A', $texto);
            $table->addCell(4000, array('gridSpan' => 2, 'valign' => 'center'))->addTextRun($centrado)->addText('B', $texto); // combina columna
            $table->addCell(2000, $combinar_fila)->addTextRun($centrado)->addText('E', $texto);
            $table->addRow(); //nueva fila
            $table->addCell(null, $continua_fila);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('C', $texto);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('D', $texto);
            $table->addCell(null, $continua_fila);
            $table->addRow(); //nueva fila
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('0', $texto);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('0', $texto);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('0', $texto);
            $table->addCell(2000, $celda)->addTextRun($centrado)->addText('0', $texto);

            // Dibujar tabla
            $plantillaword->setComplexBlock('tabla1', $table);
        */


        try {
            Storage::makeDirectory('reportes/recsensorial'); //crear directorio
            $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Informe - Reconocimiento Sensorial de Físicos - ' . $recsensorial[0]->recsensorial_instalacion . '.docx')); //crear archivo word
            // $plantillaword->saveAs(public_path('app/reportes/recsensorial/reporte1_recsensorialword.docx'));


            // respuesta
            // $dato["msj"] = 'Informacion consultada correctamente';
            // return response()->json($dato);
            return response()->download(storage_path('app/reportes/recsensorial/Informe - Reconocimiento Sensorial de Físicos - ' . $recsensorial[0]->recsensorial_instalacion) . '.docx')->deleteFileAfterSend(true);
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
    public function recsensorialreporte1wordcliente($recsensorial_id)
    {
        // OBTENER DATOS GENERALES
        //================================================================================

        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $cliente = clienteModel::findOrFail($recsensorial->cliente_id);

        // LEER PLANTILLA WORD
        //================================================================================


        // $plantillaword = new TemplateProcessor(public_path('/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_fisicoscliente.docx'));    //Ruta carpeta public
        $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Plantilla_reconocimiento_fisicoscliente.docx')); //Ruta carpeta storage


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

        // $plantillaword->setValue('FECHA_CREACION', date_format(date_create($recsensorial->recsensorial_fechainicio), 'F Y')); // date('Y-m-d H:i:s')
        // $plantillaword->setValue('FECHA_CREACION', $meses_M[(date_format(date_create($recsensorial->recsensorial_fechainicio), 'm')-1)].' del '.date_format(date_create($recsensorial->recsensorial_fechainicio), 'Y'));
        setlocale(LC_ALL, "es_MX");
        $plantillaword->setValue('FECHA_CREACION', ucfirst(strftime("%B %Y", strtotime(date("d-m-Y", strtotime($recsensorial->recsensorial_fechainicio)))))); //ucfirst = primera letra mayuscula

        $plantillaword->setValue('INSTALACION_NOMBRE', $recsensorial->recsensorial_instalacion);

        $plantillaword->setValue('EMPRESA_RESPONSABLE', $cliente->cliente_plantillaempresaresponsable);

        $plantillaword->setValue('NUMERO_CONTRATO', $cliente->cliente_numerocontrato);

        $plantillaword->setValue('CONTRATO_DESCRIPCION', $cliente->cliente_descripcioncontrato);


        $titulo_partida = clientepartidasModel::where('cliente_id', $recsensorial->cliente_id)
            ->where('clientepartidas_tipo', 1) // reconocimiento
            ->where('catprueba_id', 1) // fisicos
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


        $plantillaword->setValue('PIE_PAGINA', str_replace("\r\n", "<w:br/>", str_replace("\n\n", "<w:br/>", $cliente->cliente_plantillapiepagina . '<w:br/>' . 'FOLIO: ' . $recsensorial->recsensorial_foliofisico)));


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


        // DISEÑO TABLAS
        //================================================================================

        // FORMATO
        $font_size = 11;
        $bgColor_encabezado = '#0C3F64';
        $fuente = 'Montserrat';
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


        // TABLA METODOLOGIA
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialpruebas.recsensorial_id,
                                recsensorialpruebas.catprueba_id,
                                cat_prueba.catPrueba_Nombre
                            FROM
                                recsensorialpruebas
                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id 
                            WHERE
                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialpruebas.catprueba_id ASC');


        // CREAR TABLA
        $table = null;
        $lista_agentes = "";
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        // ANCHO COLUMNAS
        $col_1 = ($width_table * .25);
        $col_2 = ($width_table * .75);

        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Agente', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Normas y/o procedimientos', $encabezado_texto);

        // FILAS
        foreach ($sql as $key => $value) {
            $lista_normas = "";
            $normas = DB::select('SELECT
                                        cat_pruebanorma.catpruebanorma_tipo, 
                                        cat_pruebanorma.catpruebanorma_numero,
                                        cat_pruebanorma.catpruebanorma_descripcion
                                    FROM
                                        cat_pruebanorma
                                    WHERE
                                        cat_pruebanorma.cat_prueba_id = ' . $value->catprueba_id . ' 
                                        AND cat_pruebanorma.catpruebanorma_tipo LIKE "%NORMA%" 
                                    ORDER BY
                                     catpruebanorma_tipo ASC');

            // Formatear lineas
            foreach ($normas as $key2 => $norma) {
                if ($key2 < (count($normas) - 1)) {
                    $lista_normas .= '<w:p>
                                        <w:pPr>
                                            <w:jc w:val="both"/>
                                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                        </w:pPr>
                                        <w:t>' . htmlspecialchars($norma->catpruebanorma_numero . ": " . $norma->catpruebanorma_descripcion) . '</w:t>
                                    </w:p><w:br/>';
                } else {
                    $lista_normas .= '<w:t>' . htmlspecialchars($norma->catpruebanorma_numero . ": " . $norma->catpruebanorma_descripcion) . '</w:t>'; //La ultima linea, quitar BR (salto de linea)
                }
            }

            $table->addRow(); //fila
            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->catPrueba_Nombre, $textonegrita);
            $table->addCell($col_2, $celda)->addTextRun($justificado)->addText($lista_normas, $texto);
        }


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_METODOLOGIA', $table);
        $plantillaword->setComplexBlock('TABLA_METODOLOGIA_CONCLUSION', $table);



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



        // TABLA POE
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialarea.recsensorial_id,
                                recsensorialarea.id,
                                recsensorialarea.recsensorialarea_nombre,
                                (
                                    SELECT
                                        -- recsensorialareapruebas.recsensorialarea_id,
                                        -- recsensorialareapruebas.catprueba_id,
                                        GROUP_CONCAT(CONCAT("<w:br/>● ", cat_prueba.catPrueba_Nombre))
                                    FROM
                                        recsensorialareapruebas
                                        LEFT JOIN cat_prueba ON recsensorialareapruebas.catprueba_id = cat_prueba.id
                                    WHERE
                                        recsensorialareapruebas.recsensorialarea_id = recsensorialarea.id
                                ) AS agentes_riesgo,
                                recsensorialareacategorias.recsensorialcategoria_id,
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                recsensorialareacategorias.recsensorialareacategorias_actividad 
                            FROM
                                recsensorialarea
                                RIGHT JOIN recsensorialareacategorias ON recsensorialarea.id = recsensorialareacategorias.recsensorialarea_id
                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                            WHERE
                                recsensorialarea.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialarea.recsensorialarea_nombre ASC,
                                recsensorialareacategorias.recsensorialcategoria_id ASC');


        // CREAR TABLA
        $table = null;
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        // ANCHO COLUMNAS
        $col_1 = ($width_table * .06);
        $col_2 = ($width_table * .20);
        $col_3 = ($width_table * .20);
        $col_4 = ($width_table * .27);
        $col_5 = ($width_table * .27);

        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Agentes de riesgo', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría expuesta', $encabezado_texto);
        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividad', $encabezado_texto);

        // FILAS
        $numero_fila = 0;
        $area_nombre = "xxx";
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila

            if ($area_nombre != $value->recsensorialarea_nombre) {
                $numero_fila += 1;

                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell($col_3, $combinar_fila)->addTextRun($izquierda)->addText($value->agentes_riesgo, $texto);

                $area_nombre = $value->recsensorialarea_nombre;
            } else {
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $continua_fila);
                $table->addCell($col_3, $continua_fila);
            }

            $table->addCell($col_4, $celda)->addTextRun($justificado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
            $table->addCell($col_5, $celda)->addTextRun($justificado)->addText($value->recsensorialareacategorias_actividad, $texto);
        }


        $plantillaword->setComplexBlock('TABLA_POE', $table);


        // TABLA CATEGORIAS
        //================================================================================


        $sql = DB::select('SELECT
                                TABLA.recsensorialcategoria_id,
                                TABLA.recsensorialcategoria_nombrecategoria,
                                TABLA.catmovilfijo_nombre,
                                SUM(TABLA.recsensorialareacategorias_total) AS total
                            FROM
                                (
                                    SELECT
                                        recsensorialareacategorias.recsensorialcategoria_id,
                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                        IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                        catmovilfijo.catmovilfijo_nombre,
                                        recsensorialareacategorias.recsensorialareacategorias_total
                                    FROM
                                        recsensorialarea
                                        INNER JOIN recsensorialareacategorias ON recsensorialarea.id = recsensorialareacategorias.recsensorialarea_id
                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                        LEFT JOIN catmovilfijo ON recsensorialcategoria.catmovilfijo_id = catmovilfijo.id 
                                    WHERE
                                        recsensorialarea.recsensorial_id = ' . $recsensorial_id . '
                                    GROUP BY
                                        recsensorialareacategorias.recsensorialcategoria_id,
                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                        catmovilfijo.catmovilfijo_nombre,
                                        recsensorialareacategorias.recsensorialareacategorias_total
                                ) AS TABLA
                            GROUP BY
                                TABLA.recsensorialcategoria_id,
                                TABLA.recsensorialcategoria_nombrecategoria,
                                TABLA.catmovilfijo_nombre
                            ORDER BY
                                TABLA.recsensorialcategoria_id ASC');


        // CREAR TABLA
        $table = null;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        // ANCHO COLUMNAS
        $col_1 = ($width_table * .05);
        $col_2 = ($width_table * .70);
        $col_3 = ($width_table * .07);
        $col_4 = ($width_table * .18);

        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Puesto o categoría', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Tipo', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores', $encabezado_texto);


        // FILAS
        $numero_fila = 1;
        $total = 0;
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);
            $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
            $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->catmovilfijo_nombre, $texto);
            $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->total, $texto);

            $total += ($value->total + 0);
            $numero_fila += 1;
        }


        $table->addRow(); //fila
        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de trabajadores en la instalación', $textototal); // combina columna
        $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($total, $textonegrita);


        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_CATEGORIAS', $table);


        // TABLA HORARIOS
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialcategoria.id,
                                recsensorialcategoria.recsensorial_id,
                                IFNULL(catdepartamento.catdepartamento_nombre, "Sin dato" ) AS catdepartamento_nombre,
                                IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                recsensorialcategoria.recsensorialcategoria_horasjornada,
                                TIME_FORMAT(recsensorialcategoria.recsensorialcategoria_horarioentrada, "%H:%i") AS recsensorialcategoria_horarioentrada,
                                TIME_FORMAT(recsensorialcategoria.recsensorialcategoria_horariosalida, "%H:%i") AS recsensorialcategoria_horariosalida 
                            FROM
                                recsensorialcategoria
                                LEFT JOIN catdepartamento ON recsensorialcategoria.catdepartamento_id = catdepartamento.id 
                            WHERE
                                recsensorialcategoria.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                catdepartamento.catdepartamento_nombre ASC,
                                recsensorialcategoria.id ASC');

        // CREAR TABLA
        $table = null;
        $No = 1;
        $total = 0;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

        // ANCHO COLUMNAS
        $col_1 = ($width_table * .20);
        $col_2 = ($width_table * .50);
        $col_3 = ($width_table * .12);
        $col_4 = ($width_table * .18);

        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Departamento de adscripción', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Jornada (horas)', $encabezado_texto);
        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Horario de jornada', $encabezado_texto);

        // registros tabla
        $departamento = 'xxx';
        $horarios = 'xxx';
        foreach ($sql as $key => $value) {
            if ($value->recsensorialcategoria_horasjornada == 24) {
                $horarios = 'COMPLETO';
            } else {
                $horarios = $value->recsensorialcategoria_horarioentrada . ' - ' . $value->recsensorialcategoria_horariosalida;
            }


            if ($departamento != $value->catdepartamento_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->catdepartamento_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_horasjornada, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($horarios, $texto);

                $departamento = $value->catdepartamento_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_horasjornada, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($horarios, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_HORARIOS', $table);
        $plantillaword->setValue('HORARIO_OBSERVACION', $recsensorial->recsensorial_obscategorias);


        // TABLA EQUIPO DE PROTECCION PERSONAL
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialequipopp.recsensorial_id,
                                IFNULL(IF( recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", CONCAT( recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_funcioncategoria, ")" ) ), "Sin dato") AS categoria,
                                recsensorialequipopp.catpartecuerpo_id,
                                catpartecuerpo.catpartecuerpo_nombre,
                                recsensorialequipopp.recsensorialequipopp_descripcion 
                            FROM
                                recsensorialequipopp
                                LEFT JOIN recsensorialcategoria ON recsensorialequipopp.recsensorialcategoria_id = recsensorialcategoria.id
                                LEFT JOIN catpartecuerpo ON recsensorialequipopp.catpartecuerpo_id = catpartecuerpo.id 
                            WHERE
                                recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . ' 
                            ORDER BY
                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC,
                                recsensorialequipopp.catpartecuerpo_id ASC');


        // CREAR TABLA
        $table = null;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .40);
        $col_2 = ($width_table * .20);
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


        // TABLA FUENTES GENERADORAS
        //================================================================================


        $sql = DB::select('SELECT
                                recsensorialmaquinaria.id,
                                recsensorialmaquinaria.recsensorial_id,
                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                IFNULL(recsensorialmaquinaria.recsensorialmaquinaria_nombre, "Sin dato" ) AS recsensorialmaquinaria_nombre,
                                recsensorialmaquinaria.recsensorialmaquinaria_cantidad 
                            FROM
                                recsensorialmaquinaria
                                LEFT JOIN recsensorialarea ON recsensorialmaquinaria.recsensorialarea_id = recsensorialarea.id 
                            WHERE
                                recsensorialmaquinaria.recsensorial_id = ' . $recsensorial_id . '
                            ORDER BY
                                recsensorialarea.id ASC,
                                recsensorialmaquinaria.recsensorialmaquinaria_nombre ASC');


        // CREAR TABLA
        $table = null;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        // ANCHO COLUMNAS
        $col_1 = ($width_table * .30);
        $col_2 = ($width_table * .55);
        $col_3 = ($width_table * .15);


        // ENCABEZADO
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Fuentes generadoras', $encabezado_texto);
        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);


        // registros tabla
        $area = 'xxx';
        foreach ($sql as $key => $value) {
            if ($area != $value->recsensorialarea_nombre) {
                $table->addRow(); //fila
                $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);

                $area = $value->recsensorialarea_nombre;
            } else {
                $table->addRow(); //fila
                $table->addCell($col_1, $continua_fila);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_nombre, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialmaquinaria_cantidad, $texto);
            }
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_FUENTES_GENERADORAS', $table);


        // TABLAS DE LOS AGENTES EVALUADOS
        //================================================================================


        $sql = DB::select('SELECT
                                cat_prueba.id,
                                cat_prueba.catPrueba_Nombre,
                                cat_prueba.catPrueba_orden,
                                IFNULL((
                                    SELECT
                                        IF(recsensorialpruebas.catprueba_id, 1, 0)
                                    FROM
                                        recsensorialpruebas 
                                    WHERE
                                        recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' 
                                        AND recsensorialpruebas.catprueba_id = cat_prueba.id
                                ), 0) AS encontrato
                            FROM
                                cat_prueba
                            ORDER BY
                                cat_prueba.catPrueba_orden ASC');


        $numero_titulo = 0;
        foreach ($sql as $key => $valuex) {
            if ($valuex->encontrato == 0) {
                $plantillaword->setValue('titulo_agente_' . $valuex->id, '');
                $plantillaword->setValue('tabla_agente_' . $valuex->id, '');
                $plantillaword->setValue('observacion_agente_' . $valuex->id, '');

                if ($valuex->id == 1) {
                    $plantillaword->setValue('titulo_agente_1_1', '');
                    $plantillaword->setValue('titulo_agente_1_2', '');
                    $plantillaword->setValue('tabla_agente_1_2', '');
                }


                if ($valuex->id == 13) {
                    $plantillaword->setValue('observacion1_agente_13', '');
                    $plantillaword->setValue('observacion2_agente_13', '');
                }
            } else {
                $width_table = 13500;
                $numero_titulo += 1;

                switch ($valuex->id) {
                    case 1:
                        // Crear tabla 1
                        $table = null;
                        $No = 1;
                        $total = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        // $sql = DB::select('SELECT
                        //                         parametroruidosonometria.id,
                        //                         parametroruidosonometria.recsensorial_id,
                        //                         parametroruidosonometria.recsensorialarea_id,
                        //                         IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                        //                         parametroruidosonometria.parametroruidosonometria_puntos,
                        //                         IFNULL((
                        //                             SELECT
                        //                                 CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                        //                             FROM
                        //                                 parametroruidosonometriacategorias
                        //                                 LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                        //                             WHERE
                        //                                 parametroruidosonometriacategorias.recsensorialarea_id = parametroruidosonometria.recsensorialarea_id
                        //                         ), "-") AS categorias
                        //                     FROM
                        //                         parametroruidosonometria
                        //                         LEFT JOIN recsensorialarea ON parametroruidosonometria.recsensorialarea_id = recsensorialarea.id
                        //                     WHERE
                        //                         parametroruidosonometria.recsensorial_id = '.$recsensorial_id.' 
                        //                     ORDER BY
                        //                         recsensorialarea.id ASC');

                        $sql = DB::select('SELECT
                                                    tabla.recsensorial_id,
                                                    tabla.recsensorialarea_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    IFNULL((
                                                            SELECT
                                                                    CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                                                            FROM
                                                                    parametroruidosonometriacategorias
                                                                    LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                            WHERE
                                                                    parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                    ), "-") AS categorias,
                                                    tabla.parametroruidosonometria_puntos,
                                                    MIN(tabla.medicion) AS min,
                                                    MAX(tabla.medicion) AS max,
                                                    (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                    (
                                                        CASE
                                                            WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                            WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                            ELSE 0
                                                        END
                                                    ) AS aplicaevaluacion,
                                                    IFNULL((
                                                        REPLACE(GROUP_CONCAT(tabla.medicion_texto ORDER BY tabla.medicion_texto ASC), ",", "<w:br/>")
                                                    ), "NP") AS mediciones,
                                                    (
                                                        CASE
                                                                WHEN (COUNT(tabla.medicion) = 0) THEN "Se evalua"
                                                                WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 
                                                                    CASE
                                                                        WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN (CONCAT("Se evalua<w:br/>Ruido inestable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                        ELSE (CONCAT("Se evalua<w:br/>Ruido Estable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                    END
                                                                ELSE "No se evalua<w:br/>≤80 dB"
                                                        END
                                                    ) AS resultado
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmax) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, CONCAT("Max: ", parametroruidosonometria.parametroruidosonometria_medmax, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmin) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, CONCAT("Min: ", parametroruidosonometria.parametroruidosonometria_medmin, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,                       
                                                                IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med1) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, CONCAT("Med01: ", parametroruidosonometria.parametroruidosonometria_med1, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med2) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, CONCAT("Med02: ", parametroruidosonometria.parametroruidosonometria_med2, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med3) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, CONCAT("Med03: ", parametroruidosonometria.parametroruidosonometria_med3, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med4) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, CONCAT("Med04: ", parametroruidosonometria.parametroruidosonometria_med4, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med5) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, CONCAT("Med05: ", parametroruidosonometria.parametroruidosonometria_med5, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med6) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, CONCAT("Med06: ", parametroruidosonometria.parametroruidosonometria_med6, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med7) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, CONCAT("Med07: ", parametroruidosonometria.parametroruidosonometria_med7, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med8) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, CONCAT("Med08: ", parametroruidosonometria.parametroruidosonometria_med8, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med9) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, CONCAT("Med09: ", parametroruidosonometria.parametroruidosonometria_med9, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametroruidosonometria.recsensorial_id, 
                                                                parametroruidosonometria.recsensorialarea_id, 
                                                                parametroruidosonometria.parametroruidosonometria_puntos,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med10) AS medicion,
                                                                IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, CONCAT("Med10: ", parametroruidosonometria.parametroruidosonometria_med10, " dB")) AS medicion_texto
                                                            FROM
                                                                parametroruidosonometria
                                                        )
                                                    ) AS tabla
                                                    LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    tabla.recsensorial_id = 182
                                                GROUP BY
                                                    tabla.recsensorial_id,
                                                    tabla.recsensorialarea_id,
                                                    parametroruidosonometria_puntos
                                                ORDER BY
                                                    tabla.recsensorialarea_id ASC');


                        // ANCHO COLUMNAS
                        $col_1 = ($width_table * .15);
                        $col_2 = ($width_table * .45);
                        $col_3 = ($width_table * .15);
                        $col_4 = ($width_table * .15);
                        $col_5 = ($width_table * .10);


                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('NSA', $encabezado_texto);
                        $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('Resultado', $encabezado_texto);
                        $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('No. de puntos', $encabezado_texto);

                        // registros tabla
                        // $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                            $table->addCell($col_2, $celda)->addTextRun($izquierda)->addText($value->categorias, $texto);
                            $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->mediciones, $texto);
                            $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->resultado, $texto);
                            $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->parametroruidosonometria_puntos, $texto);

                            if (($value->aplicaevaluacion + 0) == 1) {
                                $total += $value->parametroruidosonometria_puntos;
                            }
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total sonometrías', $textototal); // combina columna
                        $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setValue('titulo_agente_' . $valuex->id . '_1', 'Sonometrías');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);

                        //===================================================================

                        // Crear tabla 2
                        $table = null;
                        $No = 1;
                        $total = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroruidodosimetria.id,
                                                    parametroruidodosimetria.recsensorial_id,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroruidodosimetria.parametroruidodosimetria_dosis 
                                                FROM
                                                    parametroruidodosimetria
                                                    LEFT JOIN recsensorialcategoria ON parametroruidodosimetria.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroruidodosimetria.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(8000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de dosis', $encabezado_texto);

                        // registros tabla
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroruidodosimetria_dosis, $texto);

                            $total += $value->parametroruidodosimetria_dosis;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total dosimetrías', $textototal);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id . '_2', '<w:br />Dosimetrías');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id . '_2', $table);
                        break;
                    case 2:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrovibracion.id,
                                                    parametrovibracion.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametrovibracion.parametrovibracion_puntovce,
                                                    parametrovibracion.parametrovibracion_puntoves 
                                                FROM
                                                    parametrovibracion
                                                    LEFT JOIN recsensorialarea ON parametrovibracion.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametrovibracion.recsensorialcategoria_id = recsensorialcategoria.id 
                                                WHERE
                                                    parametrovibracion.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos VCE', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos VES', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntovce, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntoves, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntovce, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrovibracion_puntoves, $texto);
                            }

                            $total1 += $value->parametrovibracion_puntovce;
                            $total2 += $value->parametrovibracion_puntoves;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total2, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />VCE: Vibraciones de cuerpo entero<w:br/>VES: Vibraciones en extremidades superiores');
                        break;
                    case 3:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrotemperatura.id,
                                                    parametrotemperatura.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametrotemperatura.parametrotemperatura_puntote,
                                                    parametrotemperatura.parametrotemperatura_puntota 
                                                FROM
                                                    parametrotemperatura
                                                    LEFT JOIN recsensorialarea ON parametrotemperatura.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametrotemperatura.recsensorialcategoria_id = recsensorialcategoria.id 
                                                WHERE
                                                    parametrotemperatura.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos TE', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos TA', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntote, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntota, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntote, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrotemperatura_puntota, $texto);
                            }

                            $total1 += $value->parametrotemperatura_puntote;
                            $total2 += $value->parametrotemperatura_puntota;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total2, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />TE: Temperaturas elevadas<w:br />TA: Temperaturas abatidas');
                        break;
                    case 4:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroiluminacion.recsensorial_id,
                                                    parametroiluminacion.id,
                                                    parametroiluminacion.recsensorialarea_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    parametroiluminacion.parametroiluminacion_puntos
                                                FROM
                                                    parametroiluminacion
                                                    LEFT JOIN recsensorialarea ON parametroiluminacion.recsensorialarea_id = recsensorialarea.id 
                                                WHERE
                                                    parametroiluminacion.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        // $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            $lista_categrias = '';
                            $sql2 = DB::select('SELECT
                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria
                                                    FROM
                                                        parametroiluminacioncategorias
                                                        LEFT JOIN recsensorialcategoria ON parametroiluminacioncategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                    WHERE
                                                        parametroiluminacioncategorias.recsensorialarea_id = ' . $value->recsensorialarea_id . '
                                                    ORDER BY
                                                        recsensorialcategoria.id ASC');

                            foreach ($sql2 as $key2 => $value2) {
                                if ($key2 != (count($sql2) - 1)) {
                                    $lista_categrias .= '● ' . $value2->recsensorialcategoria_nombrecategoria . '<w:br />';
                                } else {
                                    $lista_categrias .= '● ' . $value2->recsensorialcategoria_nombrecategoria;
                                }
                            }

                            $table->addRow(); //fila
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($lista_categrias, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroiluminacion_puntos, $texto);

                            $total1 += $value->parametroiluminacion_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 5:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroradiacionionizante.id,
                                                    parametroradiacionionizante.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroradiacionionizante.parametroradiacionionizante_fuente,
                                                    parametroradiacionionizante.parametroradiacionionizante_puntos 
                                                FROM
                                                    parametroradiacionionizante
                                                    LEFT JOIN recsensorialarea ON parametroradiacionionizante.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroradiacionionizante.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroradiacionionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de fuente', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionionizante_puntos, $texto);
                            }

                            $total1 += $value->parametroradiacionionizante_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 6:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroradiacionnoionizante.id,
                                                    parametroradiacionnoionizante.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroradiacionnoionizante.parametroradiacionnoionizante_fuente,
                                                    parametroradiacionnoionizante.parametroradiacionnoionizante_puntos 
                                                FROM
                                                    parametroradiacionnoionizante
                                                    LEFT JOIN recsensorialarea ON parametroradiacionnoionizante.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroradiacionnoionizante.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroradiacionnoionizante.recsensorial_id = ' . $recsensorial_id . '  
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de fuente', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_fuente, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroradiacionnoionizante_puntos, $texto);
                            }

                            $total1 += $value->parametroradiacionnoionizante_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 7:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroprecionesambientales.id,
                                                    parametroprecionesambientales.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametroprecionesambientales.parametroprecionesambientales_contaminante,
                                                    parametroprecionesambientales.parametroprecionesambientales_puntos 
                                                FROM
                                                    parametroprecionesambientales
                                                    LEFT JOIN recsensorialarea ON parametroprecionesambientales.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametroprecionesambientales.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroprecionesambientales.recsensorial_id = ' . $recsensorial_id . '  
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Contaminante', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_contaminante, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_contaminante, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroprecionesambientales_puntos, $texto);
                            }

                            $total1 += $value->parametroprecionesambientales_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 8:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrocalidadaire.id,
                                                    parametrocalidadaire.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrocalidadaire.parametrocalidadaire_ubicacion,
                                                    (
                                                        SELECT
                                                            CONCAT("● ", IFNULL(REPLACE(GROUP_CONCAT(REPLACE(catparametrocalidadairecaracteristica.catparametrocalidadairecaracteristica_caracteristica, ",", "ˏ")), ",", "<w:br/>● "), "Sin dato")) AS caracteristicas 
                                                        FROM
                                                            parametrocalidadairecaracteristica
                                                            LEFT JOIN catparametrocalidadairecaracteristica ON parametrocalidadairecaracteristica.catparametrocalidadairecaracteristica_id = catparametrocalidadairecaracteristica.id
                                                        WHERE
                                                            parametrocalidadairecaracteristica.parametrocalidadaire_id = parametrocalidadaire.id
                                                    ) AS caracteristicas,
                                                    parametrocalidadaire.parametrocalidadaire_puntos 
                                                FROM
                                                    parametrocalidadaire
                                                    LEFT JOIN recsensorialarea ON parametrocalidadaire.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    parametrocalidadaire.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrocalidadaire.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3300, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(5200, $encabezado_celda)->addTextRun($centrado)->addText('características a medir', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(3500, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(3300, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_ubicacion, $texto);
                                $table->addCell(5200, $celda)->addTextRun($izquierda)->addText(str_replace('ˏ', ',', $value->caracteristicas), $texto);
                                $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(3500, $continua_fila);
                                $table->addCell(3300, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_ubicacion, $texto);
                                $table->addCell(5200, $celda)->addTextRun($izquierda)->addText(str_replace('ˏ', ',', $value->caracteristicas), $texto);
                                $table->addCell(1500, $celda)->addTextRun($centrado)->addText($value->parametrocalidadaire_puntos, $texto);
                            }

                            $total1 += $value->parametrocalidadaire_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(12000, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(1500, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, '<w:br />Cada punto de CAI incluye los siguientes parámetros: temperatura de confort, velocidad y caudal del aire, humedad relativa, monóxido de carbono (CO), dióxido de carbono (CO2) y bioaerosoles (bacterias, hongos y actinomicetos).');
                        break;
                    case 9:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroagua.id,
                                                    parametroagua.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    parametroagua.parametroagua_ubicacion,
                                                    parametroagua.parametroagua_tipouso,
                                                    catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                    parametroagua.parametroagua_puntos,
                                                    IFNULL((
                                                        SELECT  
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica), ",", "<w:br />● "))
                                                        FROM
                                                            parametroaguacaracteristica
                                                            RIGHT JOIN catparametroaguacaracteristica ON parametroaguacaracteristica.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                                        WHERE
                                                            parametroaguacaracteristica.parametroagua_id = parametroagua.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametroagua
                                                    LEFT JOIN recsensorialarea ON parametroagua.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                                WHERE
                                                    parametroagua.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametroagua.parametroagua_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo uso', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_tipouso, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_tipouso, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroagua_puntos, $texto);
                            }

                            $total1 += $value->parametroagua_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 10:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrohielo.id,
                                                    parametrohielo.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrohielo.parametrohielo_ubicacion,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    parametrohielo.parametrohielo_puntos,
                                                    IFNULL((
                                                        SELECT
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica), ",", "<w:br />● "))
                                                        FROM
                                                            parametrohielocaracteristica
                                                            RIGHT JOIN catparametrohielocaracteristica ON parametrohielocaracteristica.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                        WHERE
                                                            parametrohielocaracteristica.parametrohielo_id = parametrohielo.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametrohielo
                                                    LEFT JOIN recsensorialarea ON parametrohielo.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                WHERE
                                                    parametrohielo.recsensorial_id = ' . $recsensorial_id . ' 
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrohielo.parametrohielo_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametrohielocaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catparametrohielocaracteristica_tipo, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrohielo_puntos, $texto);
                            }

                            $total1 += $value->parametrohielo_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 11:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroalimento.id,
                                                    parametroalimento.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametroalimento.parametroalimento_ubicacion,
                                                    parametroalimento.parametroalimento_puntos,
                                                    IFNULL((
                                                            SELECT
                                                                CONCAT("● ", REPLACE(GROUP_CONCAT(catparametroalimentocaracteristica.catparametroalimentocaracteristica_caracteristica), ",", "<w:br />● "))    
                                                            FROM
                                                                parametroalimentocaracteristica
                                                                RIGHT JOIN catparametroalimentocaracteristica ON parametroalimentocaracteristica.catparametroalimentocaracteristica_id = catparametroalimentocaracteristica.id
                                                            WHERE
                                                                parametroalimentocaracteristica.parametroalimento_id = parametroalimento.id
                                                    ), "Sin dato") AS analisis
                                                FROM
                                                    parametroalimento
                                                    LEFT JOIN recsensorialarea ON parametroalimento.recsensorialarea_id = recsensorialarea.id
                                                WHERE
                                                    parametroalimento.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametroalimento.parametroalimento_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(2750, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroalimento_puntos, $texto);
                            }

                            $total1 += $value->parametroalimento_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 12:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametrosuperficie.id,
                                                    parametrosuperficie.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    parametrosuperficie.parametrosuperficie_ubicacion,
                                                    parametrosuperficie.parametrosuperficie_caracteristica,
                                                    parametrosuperficie.parametrosuperficie_observacion,
                                                    parametrosuperficie.parametrosuperficie_puntos,
                                                    IFNULL((
                                                            SELECT
                                                                CONCAT("● ", REPLACE(GROUP_CONCAT(catparametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_caracteristica), ",", "<w:br />● "))    
                                                            FROM
                                                                parametrosuperficiecaracteristica
                                                                RIGHT JOIN catparametrosuperficiecaracteristica ON parametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_id = catparametrosuperficiecaracteristica.id
                                                            WHERE
                                                                parametrosuperficiecaracteristica.parametrosuperficie_id = parametrosuperficie.id
                                                    ), "Sin dato") AS analisis 
                                                FROM
                                                    parametrosuperficie
                                                    LEFT JOIN recsensorialarea ON parametrosuperficie.recsensorialarea_id = recsensorialarea.id 
                                                WHERE
                                                    parametrosuperficie.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    parametrosuperficie.parametrosuperficie_ubicacion ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Análisis', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_caracteristica, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_puntos, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_ubicacion, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_caracteristica, $texto);
                                $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->analisis, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametrosuperficie_puntos, $texto);
                            }

                            $total1 += $value->parametrosuperficie_puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 13:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametroergonomia.id,
                                                    parametroergonomia.recsensorial_id,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    IF(parametroergonomia.parametroergonomia_movimientorepetitivo = 1, "Sí", "-") AS parametroergonomia_movimientorepetitivo,
                                                    IF(parametroergonomia.parametroergonomia_posturamantenida = 1, "Sí", "-") AS parametroergonomia_posturamantenida,
                                                    IF(parametroergonomia.parametroergonomia_posturaforzada = 1, "Sí", "-") AS parametroergonomia_posturaforzada,
                                                    IF(parametroergonomia.parametroergonomia_cargamanual = 1, "Sí", "-") AS parametroergonomia_cargamanual,
                                                    IF(parametroergonomia.parametroergonomia_fuerza = 1, "Sí", "-") AS parametroergonomia_fuerza,
                                                    IFNULL(IF((parametroergonomia.parametroergonomia_movimientorepetitivo +
                                                    parametroergonomia.parametroergonomia_posturamantenida +
                                                    parametroergonomia.parametroergonomia_posturaforzada +
                                                    parametroergonomia.parametroergonomia_cargamanual +
                                                    parametroergonomia.parametroergonomia_fuerza) >= 2, 1, 0), 0) AS puntos,
                                                    IFNULL((
                                                        SELECT
                                                            CONCAT("● ", REPLACE(GROUP_CONCAT(IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato")), ",", "<w:br />● "))
                                                        FROM
                                                            parametroergonomiaarea
                                                            LEFT JOIN recsensorialarea ON parametroergonomiaarea.recsensorialarea_id = recsensorialarea.id 
                                                        WHERE
                                                            parametroergonomiaarea.parametroergonomia_id = parametroergonomia.id
                                                    ), "Sin dato") AS areas
                                                FROM
                                                    parametroergonomia
                                                    LEFT JOIN recsensorialcategoria ON parametroergonomia.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametroergonomia.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Movimi. repetitivo', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Postura mantenida', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Postura forzada', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Carga manual', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('Fuerza', $encabezado_texto);
                        $table->addCell(1000, $encabezado_celda)->addTextRun($centrado)->addText('No. puntos', $encabezado_texto);

                        // registros tabla
                        $categoria = 'xxx';
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                            $table->addCell(null, $celda)->addTextRun($izquierda)->addText($value->areas, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_movimientorepetitivo, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_posturamantenida, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_posturaforzada, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_cargamanual, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametroergonomia_fuerza, $texto);
                            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->puntos, $texto);

                            $total1 += $value->puntos;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de categorías', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setValue('observacion1_agente_' . $valuex->id, 'Para el presente recorrido sensorial se deben tener en cuenta las consideraciones estipuladas en la NOM-036-STPS-2018 y procedimiento para la evaluación e identificación de los factores de Riesgo Ergonómicos: PO-SO-TC-004-2015.<w:br />');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        $plantillaword->setValue('observacion2_agente_' . $valuex->id, '<w:br />Nota: Identifique con una marca según corresponda la presentación de cada uno de los aspectos derivados de la carga física en cada categoría de acuerdo con el recorrido sensorial realizado.');
                        break;
                    case 14:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    parametropsicosocial.id,
                                                    parametropsicosocial.recsensorial_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato" ) AS recsensorialarea_nombre,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato" ) AS recsensorialcategoria_nombrecategoria,
                                                    parametropsicosocial.parametropsicosocial_nopersonas 
                                                FROM
                                                    parametropsicosocial
                                                    LEFT JOIN recsensorialarea ON parametropsicosocial.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON parametropsicosocial.recsensorialcategoria_id = recsensorialcategoria.id
                                                WHERE
                                                    parametropsicosocial.recsensorial_id = ' . $recsensorial_id . '
                                                ORDER BY
                                                    recsensorialarea.id ASC,
                                                    recsensorialcategoria.id ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. personas', $encabezado_texto);

                        // registros tabla
                        $area = 'xxx';
                        foreach ($sql as $key => $value) {
                            if ($area != $value->recsensorialarea_nombre) {
                                $table->addRow(); //fila
                                $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametropsicosocial_nopersonas, $texto);

                                $area = $value->recsensorialarea_nombre;
                            } else {
                                $table->addRow(); //fila
                                $table->addCell(null, $continua_fila);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                                $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->parametropsicosocial_nopersonas, $texto);
                            }

                            $total1 += $value->parametropsicosocial_nopersonas;
                        }

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total evaluaciones', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total1, $textonegrita);

                        // Dibujar tabla en el word
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    case 15:
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        // $plantillaword->setValue('tabla_agente_'.$valuex->id, '');
                        // $plantillaword->setComplexBlock('tabla_agente_'.$valuex->id, $table);
                        $plantillaword->setValue('observacion_agente_' . $valuex->id, 'El reconocimiento de agentes químicos realizado y/o validado por un laboratorio de pruebas acreditado y aprobado se encuentra en el informe de Reconocimiento de agentes químicos en el ambiente laboral.');
                        break;
                    case 16:
                        // Consulta información
                        $total_serviciopersonal = DB::select('SELECT
                                                                        COUNT(parametroserviciopersonal.id) AS totalregistros,
                                                                        IFNULL(SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ), 0) AS totalpuntos 
                                                                    FROM
                                                                        parametroserviciopersonal
                                                                    WHERE
                                                                        parametroserviciopersonal.recsensorial_id = ' . $recsensorial_id);

                        if (($total_serviciopersonal[0]->totalpuntos + 0) > 1) {
                            // Mostrar datos en word
                            $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                            $plantillaword->setValue('tabla_agente_' . $valuex->id, 'Se realizará un estudio que incluya la evaluación de la infraestructura para servicios del personal contabilizando a un total de ' . $total_serviciopersonal[0]->totalpuntos . ' trabajadores por el total de la instalación.');
                        } else {
                            // Mostrar datos en word
                            $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                            $plantillaword->setValue('tabla_agente_' . $valuex->id, 'No se considerará la evaluación de Infraestructura para servicios al personal, debido a que en la instalación solamente se encuentra un trabajador por turno de trabajo.');
                        }

                        break;
                    case 17:
                        // Crear tabla
                        $table = null;
                        $No = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));

                        $sql = DB::select('SELECT
                                                    TABLA.recsensorial_id,
                                                    TABLA.parametromapariesgo_tipo,
                                                    TABLA.cantidad
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                parametromapariesgo.recsensorial_id,
                                                                "Mapa de riesgos (Tipo 1)" AS parametromapariesgo_tipo,
                                                                SUM(parametromapariesgo.parametromapariesgo_tipo1) AS cantidad
                                                            FROM
                                                                parametromapariesgo
                                                            WHERE
                                                                parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                            GROUP BY
                                                                parametromapariesgo.recsensorial_id
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                parametromapariesgo.recsensorial_id,
                                                                "Mapa de riesgos (Tipo 2)" AS parametromapariesgo_tipo,
                                                                SUM(parametromapariesgo.parametromapariesgo_tipo2) AS cantidad
                                                            FROM
                                                                parametromapariesgo
                                                            WHERE
                                                                parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                            GROUP BY
                                                                parametromapariesgo.recsensorial_id
                                                        )
                                                    ) AS TABLA
                                                WHERE
                                                    TABLA.cantidad > 0
                                                ORDER BY
                                                    TABLA.parametromapariesgo_tipo ASC');

                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell(11500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo', $encabezado_texto);
                        $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad', $encabezado_texto);

                        // registros tabla
                        $total = 0;
                        foreach ($sql as $key => $value) {
                            $table->addRow(); //fila
                            $table->addCell(11500, $celda)->addTextRun($centrado)->addText($value->parametromapariesgo_tipo, $texto);
                            $table->addCell(2000, $celda)->addTextRun($centrado)->addText($value->cantidad, $texto);

                            $total += ($value->cantidad + 0);
                        }

                        $table->addRow(); //fila
                        $table->addCell(11500, array('gridSpan' => 1, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total mapas de riesgos', $textototal); // combina columna
                        $table->addCell(2000, $celda)->addTextRun($centrado)->addText($total, $textonegrita);

                        // Dibujar tabla en el word  
                        $plantillaword->setValue('titulo_agente_' . $valuex->id, '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>8.' . $numero_titulo . '  ' . $valuex->catPrueba_Nombre . '</w:t></w:r><w:r><w:t>');
                        $plantillaword->setComplexBlock('tabla_agente_' . $valuex->id, $table);
                        break;
                    default:
                        # Code...
                        break;
                }
            }
        }


        // TABLA RESUMEN
        //================================================================================

        // Crear tabla
        $table = null;
        $width_table = 9940;
        $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


        $sql = DB::select('SELECT
                                recsensorialpruebas.recsensorial_id,
                                cat_prueba.catPrueba_Tipo,
                                TABLA1.catprueba_id,
                                IF(IFNULL(TABLA1.agente_subnombre, "") = "", cat_prueba.catPrueba_Nombre, CONCAT(cat_prueba.catPrueba_Nombre, " (", TABLA1.agente_subnombre, ")")) AS catPrueba_Nombre,
                                cat_prueba.catPrueba_Nombre AS agente_nombre,
                                TABLA1.agente_subnombre AS agente_subnombre,
                                TABLA1.totalregistros,
                                TABLA1.totalpuntos,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                -- WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                ELSE "Extra chica"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                ELSE "N/A"
                                            END
                                        )
                                        ,
                                            "N/A"
                                    )
                                ) AS tipoinstalacion,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "0"
                                                -- WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                ELSE "XC"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "0"
                                                -- WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                ELSE "N/A"
                                            END
                                        )
                                        ,
                                            "N/A"
                                    )
                                ) AS tipoinstalacion_abreviacion,
                                (
                                    IF(TABLA1.catprueba_id != 17,
                                        IF(TABLA1.catprueba_id != 16,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                -- WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                ELSE "#0080FF"
                                            END
                                            ,
                                            CASE
                                                WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                ELSE "#999999"
                                            END
                                        )
                                        ,
                                        "#999999"
                                    )
                                ) AS tipoinstalacion_color
                            FROM
                                (
                                    (
                                        SELECT
                                            TABLARUIDO.catprueba_id,
                                            NULL AS agente_subnombre,
                                            SUM(TABLARUIDO.totalregistros) AS totalregistros,
                                            SUM(TABLARUIDO.totalpuntos) AS totalpuntos
                                        FROM
                                            (
                                                (
                                                    -- SELECT
                                                    --     1 AS catprueba_id,
                                                    --     NULL AS agente_subnombre,
                                                    --     COUNT(parametroruidosonometria.recsensorial_id) AS totalregistros,
                                                    --     SUM(parametroruidosonometria.parametroruidosonometria_puntos) AS totalpuntos 
                                                    -- FROM
                                                    --     parametroruidosonometria
                                                    -- WHERE
                                                    --     parametroruidosonometria.recsensorial_id = 182
                                                    -- GROUP BY
                                                    --     parametroruidosonometria.recsensorial_id,
                                                    --     parametroruidosonometria.parametroruidosonometria_puntos

                                                    SELECT
                                                        1 AS catprueba_id,
                                                        NULL AS agente_subnombre,
                                                        COUNT(tablaresumen.recsensorialarea_id) AS totalregistros,
                                                        SUM(tablaresumen.puntos) AS totalpuntos
                                                    FROM
                                                        (
                                                            SELECT
                                                                tabla.recsensorial_id,
                                                                tabla.recsensorialarea_id,
                                                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                                IFNULL((
                                                                    SELECT
                                                                        CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<w:br />● "))
                                                                    FROM
                                                                        parametroruidosonometriacategorias
                                                                        LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                                    WHERE
                                                                        parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                                ), "-") AS categorias,
                                                                tabla.parametroruidosonometria_puntos AS puntos,
                                                                MIN(tabla.medicion) AS min,
                                                                MAX(tabla.medicion) AS max,
                                                                (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                                (
                                                                    CASE
                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                                        ELSE 0
                                                                    END
                                                                ) AS aplicaevaluacion,
                                                                IFNULL((
                                                                    REPLACE(GROUP_CONCAT(tabla.medicion_texto ORDER BY tabla.medicion_texto ASC), ",", "<w:br/>")
                                                                ), "NP") AS mediciones,
                                                                (
                                                                    CASE
                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN "Se evalua"
                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 
                                                                            CASE
                                                                                WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN (CONCAT("Se evalua<w:br/>Ruido inestable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                ELSE (CONCAT("Se evalua<w:br/>Ruido Estable<w:br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                            END
                                                                        ELSE "No se evalua<w:br/>≤80 dB"
                                                                    END
                                                                ) AS resultado
                                                            FROM
                                                                (
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmax) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, CONCAT("Max: ", parametroruidosonometria.parametroruidosonometria_medmax, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmin) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, CONCAT("Min: ", parametroruidosonometria.parametroruidosonometria_medmin, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,                       
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med1) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, CONCAT("Med01: ", parametroruidosonometria.parametroruidosonometria_med1, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med2) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, CONCAT("Med02: ", parametroruidosonometria.parametroruidosonometria_med2, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med3) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, CONCAT("Med03: ", parametroruidosonometria.parametroruidosonometria_med3, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med4) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, CONCAT("Med04: ", parametroruidosonometria.parametroruidosonometria_med4, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med5) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, CONCAT("Med05: ", parametroruidosonometria.parametroruidosonometria_med5, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med6) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, CONCAT("Med06: ", parametroruidosonometria.parametroruidosonometria_med6, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med7) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, CONCAT("Med07: ", parametroruidosonometria.parametroruidosonometria_med7, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med8) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, CONCAT("Med08: ", parametroruidosonometria.parametroruidosonometria_med8, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med9) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, CONCAT("Med09: ", parametroruidosonometria.parametroruidosonometria_med9, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            parametroruidosonometria.recsensorial_id, 
                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med10) AS medicion,
                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, CONCAT("Med10: ", parametroruidosonometria.parametroruidosonometria_med10, " dB")) AS medicion_texto
                                                                        FROM
                                                                            parametroruidosonometria
                                                                    )
                                                                ) AS tabla
                                                                LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                            WHERE
                                                                tabla.recsensorial_id = ' . $recsensorial_id . ' 
                                                            GROUP BY
                                                                tabla.recsensorial_id,
                                                                tabla.recsensorialarea_id,
                                                                parametroruidosonometria_puntos
                                                            ORDER BY
                                                                tabla.recsensorialarea_id ASC
                                                        ) AS tablaresumen
                                                    WHERE
                                                        tablaresumen.aplicaevaluacion = 1
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        1 AS catprueba_id,
                                                        NULL AS agente_subnombre,
                                                        COUNT(parametroruidodosimetria.recsensorial_id) AS totalregistros,
                                                        SUM(parametroruidodosimetria.parametroruidodosimetria_dosis) AS totalpuntos 
                                                    FROM
                                                        parametroruidodosimetria
                                                    WHERE
                                                        parametroruidodosimetria.recsensorial_id = ' . $recsensorial_id . '
                                                    GROUP BY
                                                        parametroruidodosimetria.recsensorial_id,
                                                        parametroruidodosimetria.parametroruidodosimetria_dosis
                                                )
                                            ) AS TABLARUIDO
                                        GROUP BY
                                            TABLARUIDO.catprueba_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            2 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT(parametrovibracion.recsensorial_id) AS totalregistros,
                                            SUM(parametrovibracion.parametrovibracion_puntovce + parametrovibracion.parametrovibracion_puntoves) AS totalpuntos  
                                        FROM
                                            parametrovibracion
                                        WHERE
                                            parametrovibracion.recsensorial_id = ' . $recsensorial_id . '
                                        GROUP BY
                                            parametrovibracion.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            3 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrotemperatura.recsensorial_id ) AS totalregistros,
                                            SUM( parametrotemperatura.parametrotemperatura_puntote + parametrotemperatura.parametrotemperatura_puntota ) AS totalpuntos 
                                        FROM
                                            parametrotemperatura 
                                        WHERE
                                            parametrotemperatura.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrotemperatura.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            4 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroiluminacion.recsensorial_id ) AS totalregistros,
                                            SUM( parametroiluminacion.parametroiluminacion_puntos ) AS totalpuntos 
                                        FROM
                                            parametroiluminacion 
                                        WHERE
                                            parametroiluminacion.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroiluminacion.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            5 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroradiacionionizante.recsensorial_id ) AS totalregistros,
                                            SUM( parametroradiacionionizante.parametroradiacionionizante_puntos ) AS totalpuntos 
                                        FROM
                                            parametroradiacionionizante 
                                        WHERE
                                            parametroradiacionionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroradiacionionizante.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            6 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroradiacionnoionizante.recsensorial_id ) AS totalregistros,
                                            SUM( parametroradiacionnoionizante.parametroradiacionnoionizante_puntos ) AS totalpuntos 
                                        FROM
                                            parametroradiacionnoionizante 
                                        WHERE
                                            parametroradiacionnoionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroradiacionnoionizante.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            7 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroprecionesambientales.recsensorial_id ) AS totalregistros,
                                            SUM( parametroprecionesambientales.parametroprecionesambientales_puntos ) AS totalpuntos 
                                        FROM
                                            parametroprecionesambientales 
                                        WHERE
                                            parametroprecionesambientales.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroprecionesambientales.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            8 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrocalidadaire.recsensorial_id ) AS totalregistros,
                                            SUM( parametrocalidadaire.parametrocalidadaire_puntos ) AS totalpuntos 
                                        FROM
                                            parametrocalidadaire 
                                        WHERE
                                            parametrocalidadaire.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrocalidadaire.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            9 AS catprueba_id,
                                            "Fisicoquímico" AS agente_subnombre,
                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                        FROM
                                            parametroagua
                                            INNER JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Fisicoquímico"
                                        GROUP BY
                                            parametroagua.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            9 AS catprueba_id,
                                            "Microbiológico" AS agente_subnombre,
                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                        FROM
                                            parametroagua
                                            LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Microbiológico"
                                        GROUP BY
                                            parametroagua.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            10 AS catprueba_id,
                                            "Fisicoquímico" AS agente_subnombre,
                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                        FROM
                                            parametrohielo
                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                        GROUP BY
                                            parametrohielo.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            10 AS catprueba_id,
                                            "Microbiológico" AS agente_subnombre,
                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                        FROM
                                            parametrohielo
                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Microbiológico"
                                        GROUP BY
                                            parametrohielo.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            11 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametroalimento.recsensorial_id ) AS totalregistros,
                                            SUM( parametroalimento.parametroalimento_puntos ) AS totalpuntos 
                                        FROM
                                            parametroalimento 
                                        WHERE
                                            parametroalimento.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametroalimento.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            12 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametrosuperficie.recsensorial_id ) AS totalregistros,
                                            SUM( parametrosuperficie.parametrosuperficie_puntos ) AS totalpuntos 
                                        FROM
                                            parametrosuperficie 
                                        WHERE
                                            parametrosuperficie.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametrosuperficie.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            13 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            TABLAERGONOMIA.recsensorial_id,
                                            SUM(TABLAERGONOMIA.totalpuntos) AS totalpuntos
                                        FROM
                                            (
                                                SELECT
                                                    parametroergonomia.recsensorial_id,
                                                    parametroergonomia.id,
                                                    IF((parametroergonomia.parametroergonomia_movimientorepetitivo + parametroergonomia.parametroergonomia_posturamantenida + parametroergonomia.parametroergonomia_posturaforzada + parametroergonomia.parametroergonomia_cargamanual +parametroergonomia.parametroergonomia_fuerza) > 1, 1, 0) AS totalpuntos
                                                FROM
                                                    parametroergonomia
                                                WHERE
                                                    parametroergonomia.recsensorial_id = ' . $recsensorial_id . '
                                                GROUP BY
                                                    parametroergonomia.recsensorial_id,
                                                    parametroergonomia.id,
                                                    parametroergonomia.parametroergonomia_movimientorepetitivo,
                                                    parametroergonomia.parametroergonomia_posturamantenida,
                                                    parametroergonomia.parametroergonomia_posturaforzada,
                                                    parametroergonomia.parametroergonomia_cargamanual,
                                                    parametroergonomia.parametroergonomia_fuerza
                                            ) AS TABLAERGONOMIA
                                        WHERE
                                            TABLAERGONOMIA.totalpuntos > 0
                                        GROUP BY
                                            TABLAERGONOMIA.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            14 AS catprueba_id,
                                            NULL AS agente_subnombre,
                                            COUNT( parametropsicosocial.recsensorial_id ) AS totalregistros,
                                            SUM( parametropsicosocial.parametropsicosocial_nopersonas ) AS totalpuntos 
                                        FROM
                                            parametropsicosocial 
                                        WHERE
                                            parametropsicosocial.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            parametropsicosocial.recsensorial_id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            TABLASERVICIOPERSONAL.catprueba_id,
                                            TABLASERVICIOPERSONAL.agente_subnombre,
                                            TABLASERVICIOPERSONAL.totalregistros,
                                            TABLASERVICIOPERSONAL.totalpuntos
                                        FROM
                                            (
                                                SELECT
                                                    16 AS catprueba_id,
                                                    NULL AS agente_subnombre,
                                                    COUNT( parametroserviciopersonal.recsensorial_id ) AS totalregistros,
                                                    SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ) AS totalpuntos 
                                                FROM
                                                    parametroserviciopersonal 
                                                WHERE
                                                    parametroserviciopersonal.recsensorial_id = ' . $recsensorial_id . ' 
                                                GROUP BY
                                                    parametroserviciopersonal.recsensorial_id
                                            ) AS TABLASERVICIOPERSONAL
                                        WHERE
                                            TABLASERVICIOPERSONAL.totalpuntos > 1
                                    )
                                    UNION ALL
                                      (
                                            SELECT
                                                  17 AS catprueba_id,
                                                  "Tipo 1" AS agente_subnombre,
                                                  COUNT(parametromapariesgo.parametromapariesgo_tipo1) AS totalregistros,
                                                  SUM(parametromapariesgo.parametromapariesgo_tipo1) AS totalpuntos
                                            FROM
                                                  parametromapariesgo
                                            WHERE
                                                  parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                            GROUP BY
                                                  parametromapariesgo.recsensorial_id
                                      )
                                      UNION ALL
                                      (
                                            SELECT
                                                  17 AS catprueba_id,
                                                  "Tipo 2" AS agente_subnombre,
                                                  COUNT(parametromapariesgo.parametromapariesgo_tipo2) AS totalregistros,
                                                  SUM(parametromapariesgo.parametromapariesgo_tipo2) AS totalpuntos
                                            FROM
                                                  parametromapariesgo
                                            WHERE
                                                  parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                            GROUP BY
                                                  parametromapariesgo.recsensorial_id
                                      )
                                ) TABLA1
                                LEFT JOIN recsensorialpruebas ON TABLA1.catprueba_id = recsensorialpruebas.catprueba_id
                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id
                            WHERE
                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . ' AND recsensorialpruebas.catprueba_id != 15
                            GROUP BY
                                recsensorialpruebas.recsensorial_id,
                                cat_prueba.catPrueba_Tipo,
                                TABLA1.catprueba_id,
                                cat_prueba.catPrueba_Nombre,
                                TABLA1.agente_subnombre,
                                TABLA1.totalregistros,
                                TABLA1.totalpuntos
                            ORDER BY
                                TABLA1.totalpuntos DESC,
                                cat_prueba.catPrueba_Nombre ASC');


        // encabezado tabla
        $table->addRow(200, array('tblHeader' => true));
        $table->addCell(4000, $encabezado_celda)->addTextRun($centrado)->addText('Agente de riesgo / Servicio', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Puntos de muestreos / Numero de personas / Cantidad', $encabezado_texto);
        $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de instalación', $encabezado_texto);


        // registros tabla
        foreach ($sql as $key => $value) {
            $table->addRow(); //fila
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->catPrueba_Nombre, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->totalpuntos, $texto);
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->tipoinstalacion, $texto);
        }

        // Dibujar tabla en el word
        $plantillaword->setComplexBlock('TABLA_RESUMEN_AGENTES', $table);


        // RESPONSABLES
        //================================================================================


        // RESPONSABLE 1, FOTO DOCUMENTO
        if ($recsensorial->recsensorial_repfisicos1doc) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_repfisicos1doc))) {
                $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $recsensorial->recsensorial_repfisicos1doc), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'SIN IMAGEN.');
            }
        } else {
            $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'SIN IMAGEN.');
        }


        $plantillaword->setValue('REPONSABLE1', $recsensorial->recsensorial_repfisicos1nombre . "<w:br/>" . $recsensorial->recsensorial_repfisicos1cargo);


        // RESPONSABLE 2, FOTO DOCUMENTO
        if ($recsensorial->recsensorial_repfisicos2doc) {
            if (file_exists(storage_path('app/' . $recsensorial->recsensorial_repfisicos2doc))) {
                $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $recsensorial->recsensorial_repfisicos2doc), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'SIN IMAGEN.');
            }
        } else {
            $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'SIN IMAGEN.');
        }


        $plantillaword->setValue('REPONSABLE2', $recsensorial->recsensorial_repfisicos2nombre . "<w:br/>" . $recsensorial->recsensorial_repfisicos2cargo);



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
            $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => '000000', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1,))->addTextRun($centrado)->addText('Memoria fotográfica', array('color' => '000000', 'size' => 12, 'bold' => true, 'name' => 'Arial'));
            $table->addRow(500, array('tblHeader' => true));
            $table->addCell(($col_1 + $col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Reconocimiento de agentes físicos', $encabezado_texto);


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
                                    AND recsensorialevidencias.recsensorialevidencias_tipo = 2 -- 1 = FOTOS, 2 = PLANOS
                                ORDER BY
                                    cat_prueba.catPrueba_orden ASC,
                                    recsensorialevidencias.id ASC');


        $planos_variables = '';
        if (count($planos) > 0) {
            foreach ($planos as $key => $plano) {
                // $planos_variables .= '${PLANO_'.$key.'_TITULO}<w:br/>${PLANO_'.$key.'_FOTO}<w:br/>';


                if (($key + 0) > 0) {
                    if (($key + 1) < count($planos)) // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)
                    {
                        $planos_variables .= '${PLANO_' . $key . '_TITULO}<w:br/>${PLANO_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                    } else {
                        $planos_variables .= '${PLANO_' . $key . '_TITULO}<w:br/>${PLANO_' . $key . '_FOTO}<w:br/>';
                    }
                } else {
                    if (($key + 1) < count($planos)) // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)
                    {
                        $planos_variables .= '${PLANO_' . $key . '_TITULO}<w:br/>${PLANO_' . $key . '_FOTO}<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t>';
                    } else {
                        $planos_variables .= '${PLANO_' . $key . '_TITULO}<w:br/>${PLANO_' . $key . '_FOTO}<w:br/>';
                    }
                }
            }

            $plantillaword->setValue('PLANOS', $planos_variables);
        } else {
            $plantillaword->setValue('PLANOS', 'NO HAY PLANOS QUE MOSTRAR');
        }



        // TABLA ANEXO 3, EQUIPO UTILIZADO PARA LA MEDICION
        //================================================================================


        $equipos = DB::select('SELECT
                                    parametroruidoequipos.recsensorial_id,
                                    parametroruidoequipos.id,
                                    parametroruidoequipos.proveedor_id,
                                    proveedor.proveedor_RazonSocial,
                                    parametroruidoequipos.equipo_id,
                                    equipo.equipo_Descripcion,
                                    equipo.equipo_Marca,
                                    equipo.equipo_Modelo,
                                    equipo.equipo_Serie,
                                    equipo.equipo_VigenciaCalibracion,
                                    equipo.equipo_CertificadoPDF 
                                FROM
                                    parametroruidoequipos
                                    LEFT JOIN proveedor ON parametroruidoequipos.proveedor_id = proveedor.id
                                    LEFT JOIN equipo ON parametroruidoequipos.equipo_id = equipo.id
                                WHERE
                                    parametroruidoequipos.recsensorial_id = ' . $recsensorial_id . ' 
                                ORDER BY
                                    proveedor.proveedor_RazonSocial ASC,
                                    equipo.equipo_Descripcion ASC,
                                    equipo.equipo_Marca ASC,
                                    equipo.equipo_Modelo ASC,
                                    equipo.equipo_Serie ASC');


        if (count($equipos) == 0) {
            // CREAR TABLA
            $table = null;
            $width_table = 9940;
            $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 11, 'borderColor' => '000000', 'cellMargin' => 0, 'unit' => TblWidth::TWIP));


            // COLUMNAS
            $col_1 = ($width_table * .20);
            $col_2 = ($width_table * .20);
            $col_3 = ($width_table * .20);
            $col_4 = ($width_table * .20);
            $col_5 = ($width_table * .20);


            // ENCABEZADO
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($col_1, $encabezado_celda)->addTextRun($centrado)->addText('Equipo', $encabezado_texto);
            $table->addCell($col_2, $encabezado_celda)->addTextRun($centrado)->addText('Marca', $encabezado_texto);
            $table->addCell($col_3, $encabezado_celda)->addTextRun($centrado)->addText('Modelo', $encabezado_texto);
            $table->addCell($col_4, $encabezado_celda)->addTextRun($centrado)->addText('No. de serie', $encabezado_texto);
            $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Vigencia de<w:br/>calibración', $encabezado_texto);


            foreach ($equipos as $key => $value) {
                $table->addRow(); //fila

                $table->addCell($col_1, $celda)->addTextRun($centrado)->addText($value->equipo_Descripcion, $texto);
                $table->addCell($col_2, $celda)->addTextRun($centrado)->addText($value->equipo_Marca, $texto);
                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->equipo_Modelo, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->equipo_Serie, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->equipo_VigenciaCalibracion, $texto);
            }


            $plantillaword->setValue('TABLA_EQUIPO_TITULO', 'Equipo utilizado en la medición');
            $plantillaword->setComplexBlock('TABLA_EQUIPO_UTILIZADO', $table);

            $plantillaword->setValue('EQUIPO_UTILIZADO_NOTA', '');
            $plantillaword->setValue('EQUIPO_UTILIZADO_DESCRIPCION', '');
        } else {
            $plantillaword->setValue('TABLA_EQUIPO_TITULO', '');
            $plantillaword->setValue('TABLA_EQUIPO_UTILIZADO', '');

            $plantillaword->setValue('EQUIPO_UTILIZADO_NOTA', '<w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/><w:br/>Nota aclaratoria:<w:br/>');
            $plantillaword->setValue('EQUIPO_UTILIZADO_DESCRIPCION', 'Para este estudio no se utilizaron equipos para realizar la volumetría de los puntos de medición; solamente se siguieron los criterios técnicos de las normas aplicables para los agentes de riesgo físicos reconocidos en la instalación.');
        }



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CREAR WORD TEMPORAL


        // GUARDAR
        Storage::makeDirectory('reportes/recsensorial'); //crear directorio
        $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_foliofisico . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

        // sleep(1);

        // ABRIR NUEVA PLANTILLA
        $plantillaword = new TemplateProcessor(storage_path('app/reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_foliofisico . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        // TABLA ANEXO 1, Memoria fotográfica - AGREGAR FOTOS
        //================================================================================


        for ($i = 0; $i < count($fotos); $i += 4) {
            if ($i < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . $i, array('path' => storage_path('app/' . $fotos[$i]->recsensorialevidencias_foto), 'height' => 270, 'width' => 270, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . $i, 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . $i . '_DESCRIPCION', $fotos[$i]->recsensorialevidencias_descripcion);
            }


            if (($i + 1) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 1), array('path' => storage_path('app/' . $fotos[($i + 1)]->recsensorialevidencias_foto), 'height' => 270, 'width' => 270, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . ($i + 1), 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . ($i + 1) . '_DESCRIPCION', $fotos[($i + 1)]->recsensorialevidencias_descripcion);
            }


            if (($i + 2) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 2), array('path' => storage_path('app/' . $fotos[($i + 2)]->recsensorialevidencias_foto), 'height' => 270, 'width' => 270, 'ratio' => false));
                } else {
                    $plantillaword->setValue('FOTO_' . ($i + 2), 'NO SE ENCONTRÓ LA FOTO');
                }

                $plantillaword->setValue('FOTO_' . ($i + 2) . '_DESCRIPCION', $fotos[($i + 2)]->recsensorialevidencias_descripcion);
            }


            if (($i + 3) < count($fotos)) {
                if (Storage::exists($fotos[$i]->recsensorialevidencias_foto)) {
                    $plantillaword->setImageValue('FOTO_' . ($i + 3), array('path' => storage_path('app/' . $fotos[($i + 3)]->recsensorialevidencias_foto), 'height' => 270, 'width' => 270, 'ratio' => false));
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
                $plantillaword->setValue('PLANO_' . $i . '_TITULO', $planos[$i]->recsensorialevidencias_descripcion);
                $plantillaword->setImageValue('PLANO_' . $i . '_FOTO', array('path' => storage_path('app/' . $planos[$i]->recsensorialevidencias_foto), 'height' => 700, 'width' => 630, 'ratio' => false));
            } else {
                $plantillaword->setValue('PLANO_' . $i . '_TITULO', '');
                $plantillaword->setValue('PLANO_' . $i . '_FOTO', 'NO SE ENCONTRÓ EL PLANO ' . $i);
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
                                    AND recsensorialanexo.recsensorialanexo_tipo = 1'); // 1 = FISICOS, 2 = QUIMICOS



        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // GUARDAR Y DESCARGAR INFORME FINAL


        $informe_nombre = 'Reconocimiento Sensorial de Físicos ' . $recsensorial->recsensorial_foliofisico . ' (' . $recsensorial->recsensorial_instalacion . ').docx';


        // GUARDAR WORD FINAL
        $plantillaword->saveAs(storage_path('app/reportes/recsensorial/' . $informe_nombre)); //crear archivo word


        // ELIMINAR TEMPORAL
        if (Storage::exists('reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_foliofisico . '_TEMPORAL.docx')) {
            Storage::delete('reportes/recsensorial/Reconocimiento_' . $recsensorial->recsensorial_foliofisico . '_TEMPORAL.docx');
        }



        //================================================================================
        // CREAR .ZIP



        // Define Dir Folder
        $zip_ruta = storage_path('app/reportes/recsensorial');

        // Zip File Name
        $zip_nombre = 'Reconocimiento Sensorial de Físicos ' . $recsensorial->recsensorial_foliofisico . ' (' . $recsensorial->recsensorial_instalacion . ') + Anexos.zip';

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
            // $plantillaword->saveAs(storage_path('app/reportes/recsensorial/Reconocimiento Sensorial de Físicos '.$recsensorial->recsensorial_foliofisico.' ('.$recsensorial->recsensorial_instalacion.').docx')); //crear archivo word
            // $plantillaword->saveAs(public_path('app/reportes/recsensorial/Reconocimiento Sensorial de Físicos '.$recsensorial->recsensorial_foliofisico.' ('.$recsensorial->recsensorial_instalacion.').docx'));


            // DESCARGA
            //-------------------------------------------

            // ARCHIVO
            // return response()->download(storage_path('app/reportes/recsensorial/Reconocimiento Sensorial de Físicos '.$recsensorial->recsensorial_foliofisico.' ('.$recsensorial->recsensorial_instalacion.').docx'))->deleteFileAfterSend(true);
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
