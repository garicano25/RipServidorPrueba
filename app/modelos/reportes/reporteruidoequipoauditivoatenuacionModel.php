<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoequipoauditivoatenuacionModel extends Model
{
    protected $table = 'reporteruidoequipoauditivoatenuacion';
	protected $fillable = [
		  'reporteruidoequipoauditivo_id'
		, 'reporteruidoequipoauditivoatenuacion_bandaNRR'
		, 'reporteruidoequipoauditivoatenuacion_bandaatenuacion'
	];
}