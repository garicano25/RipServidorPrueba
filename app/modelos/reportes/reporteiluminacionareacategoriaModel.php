<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacionareacategoriaModel extends Model
{
    protected $table = 'reporteiluminacionareacategoria';
	protected $fillable = [
		  'reporteiluminacionarea_id'
		, 'reporteiluminacioncategoria_id'
		, 'reporteiluminacionareacategoria_poe'
		, 'reporteiluminacionareacategoria_total'
		, 'reporteiluminacionareacategoria_geo'
		, 'reporteiluminacionareacategoria_actividades'
		, 'reporteiluminacionareacategoria_tareavisual'
	];
}