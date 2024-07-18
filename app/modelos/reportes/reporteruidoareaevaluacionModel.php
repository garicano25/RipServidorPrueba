<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoareaevaluacionModel extends Model
{
    protected $table = 'reporteruidoareaevaluacion';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteruidoarea_id'
		, 'reporteruidoareaevaluacion_noevaluaciones'
		, 'reporteruidoareaevaluacion_nomedicion1'
		, 'reporteruidoareaevaluacion_nomedicion2'
		, 'reporteruidoareaevaluacion_ubicacion'
	];
}