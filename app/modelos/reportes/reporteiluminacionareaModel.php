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
	];
}