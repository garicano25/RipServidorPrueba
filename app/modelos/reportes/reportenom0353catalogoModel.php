<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportenom0353catalogoModel extends Model
{
    protected $table = 'reportenom0353catalogo';
    
	protected $fillable = [
		  'reportenom0353catalogo_catregion_activo'
		, 'reportenom0353catalogo_catsubdireccion_activo'
		, 'reportenom0353catalogo_catgerencia_activo'
		, 'reportenom0353catalogo_catactivo_activo'
		, 'reportenom0353catalogo_introduccion'
		, 'reportenom0353catalogo_objetivogeneral'
		, 'reportenom0353catalogo_objetivoespecifico'
		, 'reportenom0353catalogo_metodologia'
		, 'reportenom0353catalogo_ubicacioninstalacion'
		, 'reportenom0353catalogo_metodoevaluacion'
		, 'reportenom0353catalogo_conclusion'
	];
}
