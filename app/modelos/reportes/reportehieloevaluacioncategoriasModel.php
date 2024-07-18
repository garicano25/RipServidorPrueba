<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehieloevaluacioncategoriasModel extends Model
{
    protected $table = 'reportehieloevaluacioncategorias';
	protected $fillable = [
		  'reportehieloevaluacion_id'
		, 'reportehielocategoria_id'
		, 'reportehieloevaluacioncategorias_nombre'
		, 'reportehieloevaluacioncategorias_ficha'
	];
}
