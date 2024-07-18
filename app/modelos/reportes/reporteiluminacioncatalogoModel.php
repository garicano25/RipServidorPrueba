<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacioncatalogoModel extends Model
{
    protected $table = 'reporteiluminacioncatalogo';
	protected $fillable = [
		  'reporteiluminacioncatalogo_catregion_activo'
		, 'reporteiluminacioncatalogo_catsubdireccion_activo'
		, 'reporteiluminacioncatalogo_catgerencia_activo'
		, 'reporteiluminacioncatalogo_catactivo_activo'
		, 'reporteiluminacioncatalogo_introduccion'
		, 'reporteiluminacioncatalogo_objetivogeneral'
		, 'reporteiluminacioncatalogo_objetivoespecifico'
		, 'reporteiluminacioncatalogo_metodologia_4_1'
		, 'reporteiluminacioncatalogo_metodologia_4_2'
		, 'reporteiluminacioncatalogo_metodologia_4_2_1'
		, 'reporteiluminacioncatalogo_metodologia_4_2_2'
		, 'reporteiluminacioncatalogo_metodologia_4_2_3'
		, 'reporteiluminacioncatalogo_metodologia_4_2_4'
		, 'reporteiluminacioncatalogo_ubicacioninstalacion'
		, 'reporteiluminacioncatalogo_criterioseleccion'
		, 'reporteiluminacioncatalogo_conclusion'
	];
}
