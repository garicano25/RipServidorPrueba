<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacionareaModel extends Model
{
    protected $table = 'reporteiluminacionarea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reporteiluminacionarea_numorden'
		, 'reporteiluminacionarea_nombre'
		, 'reporteiluminacionarea_instalacion'
		, 'reporteiluminacionarea_puntos_ic'
		, 'reporteiluminacionarea_puntos_pt'
		, 'reporteiluminacionarea_sistemailuminacion'
		, 'reporteiluminacionarea_luznatural'
		, 'reporteiluminacionarea_iluminacionlocalizada'
		, 'reporteiluminacionarea_porcientooperacion'
		, 'reporteiluminacionarea_colorsuperficie'
		, 'reporteiluminacionarea_tiposuperficie'
		, 'reporteiluminacionarea_largo'
		, 'reporteiluminacionarea_ancho'
		, 'reporteiluminacionarea_alto'

		, 'reporteiluminacionarea_criterio '
		, 'reporteiluminacionarea_colortecho'
		, 'reporteiluminacionarea_paredes'
		, 'reporteiluminacionarea_colorpiso'
		, 'reporteiluminacionarea_superficietecho'
		, 'reporteiluminacionarea_superficieparedes'
		, 'reporteiluminacionarea_superficiepiso'
		, 'reporteiluminacionarea_potenciaslamparas'
		, 'reporteiluminacionarea_numlamparas'
		, 'reporteiluminacionarea_alturalamparas'
		, 'reporteiluminacionarea_programamantenimiento'
		, 'reporteiluminacionarea_tipoiluminacion'
		, 'reporteiluminacionarea_descripcion'

	];
}