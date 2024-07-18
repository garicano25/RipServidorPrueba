<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicoscatalogoModel extends Model
{
    protected $table = 'reportequimicoscatalogo';
    
	protected $fillable = [
		  'reportequimicoscatalogo_catregion_activo'
		, 'reportequimicoscatalogo_catsubdireccion_activo'
		, 'reportequimicoscatalogo_catgerencia_activo'
		, 'reportequimicoscatalogo_catactivo_activo'
		, 'reportequimicoscatalogo_introduccion'
		, 'reportequimicoscatalogo_objetivogeneral'
		, 'reportequimicoscatalogo_objetivoespecifico'
		, 'reportequimicoscatalogo_metodologia_4_1'
		, 'reportequimicoscatalogo_metodologia_4_2'
		, 'reportequimicoscatalogo_ubicacioninstalacion'
		, 'reportequimicoscatalogo_conclusion'
	];
}
