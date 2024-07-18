<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catviaingresoorganismoModel extends Model
{
    protected $table = 'catviaingresoorganismo';
	protected $fillable = [
		'catviaingresoorganismo_viaingreso',
		'catviaingresoorganismo_ponderacion',
		'catviaingresoorganismo_poe',
		'catviaingresoorganismo_horasexposicion',
		'catviaingresoorganismo_activo'
	];
}
