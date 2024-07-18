<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehieloevaluacionparametrosModel extends Model
{
    protected $table = 'reportehieloevaluacionparametros';
	protected $fillable = [
		  'reportehieloevaluacion_id'
		, 'catparametrohielocaracteristica_id'
		, 'reportehieloevaluacionparametros_metodo'
		, 'reportehieloevaluacionparametros_obtenida'
		, 'reportehieloevaluacionparametros_resultado'
	];
}
