<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidopuntonerfrecuenciasModel extends Model
{
    protected $table = 'reporteruidopuntonerfrecuencias';
	protected $fillable = [
		  'reporteruidopuntoner_id'
		, 'reporteruidopuntonerfrecuencias_orden'
		, 'reporteruidopuntonerfrecuencias_frecuencia'
		, 'reporteruidopuntonerfrecuencias_nivel'
	];
}
