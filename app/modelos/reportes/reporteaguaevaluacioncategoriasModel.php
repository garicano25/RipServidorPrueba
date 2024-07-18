<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguaevaluacioncategoriasModel extends Model
{
    protected $table = 'reporteaguaevaluacioncategorias';
	protected $fillable = [
		  'reporteaguaevaluacion_id'
		, 'reporteaguacategoria_id'
		, 'reporteaguaevaluacioncategorias_nombre'
		, 'reporteaguaevaluacioncategorias_ficha'
	];
}
