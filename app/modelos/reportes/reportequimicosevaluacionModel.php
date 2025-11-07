<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosevaluacionModel extends Model
{
    protected $table = 'reportequimicosevaluacion';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reportequimicosarea_id'
		, 'reportequimicoscategoria_id'
		, 'reportequimicosevaluacion_punto'
		, 'reportequimicosevaluacion_nombre'
		, 'reportequimicosevaluacion_ficha'
		, 'reportequimicosevaluacion_geo'
		, 'reportequimicosevaluacion_total'
		
		, 'reportequimicosevaluacion_anios'
		, 'reportequimicosevaluacion_antiguedadgeneral'
		, 'reportequimicosevaluacion_antiguedadcategoria'
		, 'reportequimicosevaluacion_horariotrabajo'
	];
}
