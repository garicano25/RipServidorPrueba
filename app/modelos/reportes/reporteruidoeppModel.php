<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoeppModel extends Model
{
    protected $table = 'reporteruidoepp';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteruidoepp_partecuerpo'
		, 'reporteruidoepp_equipo'
	];
}
