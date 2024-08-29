<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosevaluacionparametroModel extends Model
{
	protected $table = 'reportequimicosevaluacionparametro';
	protected $fillable = [
		'reportequimicosevaluacion_id',
		'reportequimicosevaluacionparametro_parametro',
		'reportequimicosevaluacionparametro_metodo',
		'reportequimicosevaluacionparametro_concentracion',
		'reportequimicosevaluacionparametro_valorlimite',
		'reportequimicosevaluacionparametro_limitesuperior',
		'reportequimicosevaluacionparametro_periodo',
		'reportequimicosevaluacionparametro_unidad'
	];
}
