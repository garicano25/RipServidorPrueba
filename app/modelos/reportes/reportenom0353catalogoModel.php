<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportenom0353catalogoModel extends Model
{
    protected $table = 'reporteruidocatalogo';
    
	protected $fillable = [
		  'reporteruidocatalogo_catregion_activo'
		, 'reporteruidocatalogo_catsubdireccion_activo'
		, 'reporteruidocatalogo_catgerencia_activo'
		, 'reporteruidocatalogo_catactivo_activo'
		, 'reporteruidocatalogo_introduccion'
		, 'reporteruidocatalogo_objetivogeneral'
		, 'reporteruidocatalogo_objetivoespecifico'
		, 'reporteruidocatalogo_metodologia_4_1'
		, 'reporteruidocatalogo_metodologia_4_2'
		, 'reporteruidocatalogo_ubicacioninstalacion'
		, 'reporteruidocatalogo_metodoevaluacion'
		, 'reporteruidocatalogo_conclusion'
	];
}
