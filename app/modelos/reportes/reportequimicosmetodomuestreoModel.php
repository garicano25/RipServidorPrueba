<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosmetodomuestreoModel extends Model
{
    protected $table = 'reportequimicosmetodomuestreo';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reportequimicosmetodomuestreo_parametro'
		, 'reportequimicosmetodomuestreo_puntos'
		, 'reportequimicosmetodomuestreo_metodo'
		, 'reportequimicosmetodomuestreo_tipo'
		, 'reportequimicosmetodomuestreo_orden'
		, 'reportequimicosmetodomuestreo_flujo'
	];
}
