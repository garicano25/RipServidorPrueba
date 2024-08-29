$instalacion = 'xxxx'; $area = 'XXXX'; $categoria = 'XXXX';
foreach ($sql as $key => $value)
{
if($instalacion != $value->reportearea_instalacion)
{
if (($key+0) != 0)
{
$total = DB::select('SELECT
reportetemperaturaevaluacion.proyecto_id,
reportearea.reportearea_instalacion,
COUNT(reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto) AS total
FROM
reportetemperaturaevaluacion
LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
WHERE
reportetemperaturaevaluacion.proyecto_id = '.$proyecto_id.'
AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "'.$instalacion.'"
GROUP BY
reportetemperaturaevaluacion.proyecto_id,
reportearea.reportearea_instalacion');

$table->addRow(); //fila
$table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
$table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal); // combina columna
}

// encabezado tabla
$table->addRow(200, array('tblHeader' => true));
$table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No. de puntos evaluados', $encabezado_texto);
$table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
$table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
$table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Puesto', $encabezado_texto);
$table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
$table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Régimen de trabajo', $encabezado_texto);

$table->addRow(); //fila
$table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna

$instalacion = $value->reportearea_instalacion;
}

$table->addRow(); //fila

if($area != $value->reportearea_nombre)
{
$numero_fila += 1;
$table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->total_puntosarea, $texto);
$table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);

// Reinicia la categoría cuando el área cambia
$categoria = 'XXXX';

$area = $value->reportearea_nombre;
}
else
{
$table->addCell($ancho_col_1, $continua_fila);
$table->addCell($ancho_col_2, $continua_fila);
}

if($categoria != $value->reportecategoria_nombre || $area != $value->reportearea_nombre)
{
$table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
$table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reportetemperaturaevaluacion_puesto, $texto);
$table->addCell($ancho_col_5, $combinar_fila)->addTextRun($justificado)->addText($value->reporteareacategoria_actividades, $texto);
$table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->regimen_texto, $texto);

$categoria = $value->reportecategoria_nombre;
}
else
{
$table->addCell($ancho_col_3, $continua_fila);
$table->addCell($ancho_col_4, $continua_fila);
$table->addCell($ancho_col_5, $continua_fila);
$table->addCell($ancho_col_6, $continua_fila);
}
}

$total = DB::select('SELECT
reportetemperaturaevaluacion.proyecto_id,
reportearea.reportearea_instalacion,
COUNT(reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto) AS total
FROM
reportetemperaturaevaluacion
LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
WHERE
reportetemperaturaevaluacion.proyecto_id = '.$proyecto_id.'
AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "'.$instalacion.'"
GROUP BY
reportetemperaturaevaluacion.proyecto_id,
reportearea.reportearea_instalacion');

if (count($sql) > 0)
{
$table->addRow(); //fila
$table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
$table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal); // combina columna
}