<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteairecatalogoModel extends Model
{
    protected $table = 'reporteairecatalogo';
	protected $fillable = [
		  'reporteairecatalogo_catregion_activo'
		, 'reporteairecatalogo_catsubdireccion_activo'
		, 'reporteairecatalogo_catgerencia_activo'
		, 'reporteairecatalogo_catactivo_activo'
		, 'reporteairecatalogo_introduccion'
		, 'reporteairecatalogo_objetivogeneral'
		, 'reporteairecatalogo_objetivoespecifico'
		, 'reporteairecatalogo_metodologia_4_1'
		, 'reporteairecatalogo_metodologia_4_2'
		, 'reporteairecatalogo_ubicacioninstalacion'
		, 'reporteairecatalogo_conclusion'
	];
}
