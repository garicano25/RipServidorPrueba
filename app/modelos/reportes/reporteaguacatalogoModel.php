<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguacatalogoModel extends Model
{
	protected $table = 'reporteaguacatalogo';
    protected $fillable = [
		  'reporteaguacatalogo_catregion_activo'
		, 'reporteaguacatalogo_catsubdireccion_activo'
		, 'reporteaguacatalogo_catgerencia_activo'
		, 'reporteaguacatalogo_catactivo_activo'
		, 'reporteaguacatalogo_introduccion'
		, 'reporteaguacatalogo_introduccion2'
		, 'reporteaguacatalogo_objetivogeneral'
		, 'reporteaguacatalogo_objetivoespecifico'
		, 'reporteaguacatalogo_objetivoespecifico2'
		, 'reporteaguacatalogo_metodologia_4_1'
		, 'reporteaguacatalogo_metodologia_4_2'
		, 'reporteaguacatalogo_metodologia_4_3'
		, 'reporteaguacatalogo_metodologia_4_32'
		, 'reporteaguacatalogo_metodologia_4_3_1'
		, 'reporteaguacatalogo_ubicacioninstalacion'
		, 'reporteaguacatalogo_procesoelaboracion'
		, 'reporteaguacatalogo_conclusion'
		, 'reporteaguacatalogo_conclusion2'
    ];
}
