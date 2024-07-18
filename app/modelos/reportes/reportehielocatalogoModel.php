<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehielocatalogoModel extends Model
{
    protected $table = 'reportehielocatalogo';
	protected $fillable = [
		  'reportehielocatalogo_catregion_activo'
		, 'reportehielocatalogo_catsubdireccion_activo'
		, 'reportehielocatalogo_catgerencia_activo'
		, 'reportehielocatalogo_catactivo_activo'
		, 'reportehielocatalogo_introduccion'
		, 'reportehielocatalogo_introduccion2'
		, 'reportehielocatalogo_objetivogeneral'
		, 'reportehielocatalogo_objetivoespecifico'
		, 'reportehielocatalogo_objetivoespecifico2'
		, 'reportehielocatalogo_metodologia_4_1'
		, 'reportehielocatalogo_metodologia_4_12'
		, 'reportehielocatalogo_metodologia_4_2'
		, 'reportehielocatalogo_metodologia_4_22'
		, 'reportehielocatalogo_metodologia_4_3'
		, 'reportehielocatalogo_metodologia_4_32'
		, 'reportehielocatalogo_ubicacioninstalacion'
		, 'reportehielocatalogo_procesoelaboracion'
		, 'reportehielocatalogo_conclusion'
		, 'reportehielocatalogo_conclusion2'
	];
}
