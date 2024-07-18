<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaireevaluacioncategoriasModel extends Model
{
    protected $table = 'reporteaireevaluacioncategorias';
	protected $fillable = [
		  'reporteaireevaluacion_id'
		, 'reporteairecategoria_id'
		, 'reporteaireevaluacioncategorias_nombre'
		, 'reporteaireevaluacioncategorias_ficha'
		, 'reporteaireevaluacioncategorias_geo'
	];
}
