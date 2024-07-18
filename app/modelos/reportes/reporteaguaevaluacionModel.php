<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguaevaluacionModel extends Model
{
    protected $table = 'reporteaguaevaluacion';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteaguaarea_id'
		, 'reporteaguaevaluacion_punto'
		, 'reporteaguaevaluacion_fecha'
		, 'reporteaguaevaluacion_ubicacion'
		, 'reporteaguaevaluacion_suministro'
		, 'reporteaguaevaluacion_tipouso'
		, 'reporteaguaevaluacion_descripcionuso'
		, 'reporteaguaevaluacion_condiciones'
		, 'reporteaguaevaluacion_totalpersonas'
		, 'reporteaguaevaluacion_geo'
		, 'reporteaguaevaluacion_foliomuestra'
		, 'reporteaguaevaluacion_tipoevaluacion'
	];
}
