<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidonivelsonoroModel extends Model
{
    protected $table = 'reporteruidonivelsonoro';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteruidonivelsonoro_punto'
		, 'reporteruidonivelsonoro_ubicacion'
		, 'reporteruidonivelsonoro_promedio'
		, 'reporteruidonivelsonoro_totalperiodos'
		, 'reporteruidonivelsonoro_totalresultados'
		, 'reporteruidonivelsonoro_periodo1'
		, 'reporteruidonivelsonoro_periodo2'
		, 'reporteruidonivelsonoro_periodo3'
		, 'reporteruidonivelsonoro_periodo4'
		, 'reporteruidonivelsonoro_periodo5'
	];
}
