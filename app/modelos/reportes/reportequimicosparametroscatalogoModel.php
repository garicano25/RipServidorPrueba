<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosparametroscatalogoModel extends Model
{
    protected $table = 'reportequimicosparametroscatalogo';
	protected $fillable = [
		  'reportequimicosparametroscatalogo_parametro'
		, 'reportequimicosparametroscatalogo_cas'
		, 'reportequimicosparametroscatalogo_ebullicion'
		, 'reportequimicosparametroscatalogo_pesomolecular'
		, 'reportequimicosparametroscatalogo_estadofisico'
		, 'reportequimicosparametroscatalogo_viaingreso'
		, 'reportequimicosparametroscatalogo_gradoriesgo'
		, 'reportequimicosparametroscatalogo_limiteexposicion'
	];
}
