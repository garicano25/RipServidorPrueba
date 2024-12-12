<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportenom0352catalogoModel extends Model
{
    protected $table = 'reportenom0352catalogo';
    
	protected $fillable = [
		  'reportenom0352catalogo_catregion_activo'
		, 'reportenom0352catalogo_catsubdireccion_activo'
		, 'reportenom0352catalogo_catgerencia_activo'
		, 'reportenom0352catalogo_catactivo_activo'
		, 'reportenom0352catalogo_objetivogeneral'
		, 'reportenom0352catalogo_objetivoespecifico'
		, 'reportenom0352catalogo_metodologiainstrumentos'
		, 'reportenom0352catalogo_ubicacioninstalacion'
		, 'reportenom0352catalogo_metodoevaluacion'
	];
}
