<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacioncategoriaModel extends Model
{
	protected $table = 'reporteiluminacioncategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reporteiluminacioncategoria_nombre'
		, 'reporteiluminacioncategoria_total'
		, 'reporteiluminacioncategoria_horas'
	];
}