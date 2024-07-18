<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguaevaluacionparametrosModel extends Model
{
    protected $table = 'reporteaguaevaluacionparametros';
	protected $fillable = [
		  'reporteaguaevaluacion_id'
		, 'catparametroaguacaracteristica_id'
		, 'reporteaguaevaluacionparametros_metodo'
		, 'reporteaguaevaluacionparametros_obtenida'
		, 'reporteaguaevaluacionparametros_resultado'
	];
}
