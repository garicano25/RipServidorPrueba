<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteareaModel extends Model
{
    protected $table = 'reportearea';
	protected $fillable = [
		  'proyecto_id'
		, 'recsensorialarea_id'
		, 'reportearea_instalacion'
		, 'reportearea_nombre'
		, 'reportearea_orden'
		, 'reportearea_porcientooperacion'
		
		, 'reporteiluminacionarea_porcientooperacion' //Iluminación
		, 'reportearea_puntos_ic' //Iluminación
		, 'reportearea_puntos_pt' //Iluminación
		, 'reportearea_sistemailuminacion' //Iluminación
		, 'reportearea_luznatural' //Iluminación
		, 'reportearea_iluminacionlocalizada' //Iluminación
		, 'reportearea_colorsuperficie' //Iluminación
		, 'reportearea_tiposuperficie' //Iluminación
		, 'reportearea_largo' //Iluminación
		, 'reportearea_ancho' //Iluminación
		, 'reportearea_alto' //Iluminación
		
		, 'reporteruidoarea_porcientooperacion' //Ruido
		, 'reportearea_proceso' //Ruido
		, 'reportearea_tiporuido' //Ruido
		, 'reportearea_evaluacion' //Ruido
		, 'reportearea_LNI_1' //Ruido
		, 'reportearea_LNI_2' //Ruido
		, 'reportearea_LNI_3' //Ruido
		, 'reportearea_LNI_4' //Ruido
		, 'reportearea_LNI_5' //Ruido
		, 'reportearea_LNI_6' //Ruido
		, 'reportearea_LNI_7' //Ruido
		, 'reportearea_LNI_8' //Ruido
		, 'reportearea_LNI_9' //Ruido
		, 'reportearea_LNI_10' //Ruido

		, 'reportequimicosarea_porcientooperacion' //Quimicos
		, 'reportearea_caracteristica' //Quimicos
		, 'reportearea_maquinaria' //Quimicos
		, 'reportearea_contaminante' //Quimicos

		, 'reporteairearea_porcientooperacion' //Aire
		, 'reportearea_ventilacionsistema' //Aire
		, 'reportearea_ventilacioncaracteristica' //Aire
		, 'reportearea_ventilacioncantidad' //Aire

		, 'reporteaguaarea_porcientooperacion' //Agua
		
		, 'reportehieloarea_porcientooperacion' //Hielo

		, 'reportetemperaturaarea_porcientooperacion' //Temperatura
		, 'reportearea_caracteristicaarea' //Temperatura
		, 'reportearea_tipoventilacion' //Temperatura

		, 'reportevibracionarea_porcientooperacion' //Vibracion
		, 'reportearea_tipoexposicion' //Vibracion
	];
}
