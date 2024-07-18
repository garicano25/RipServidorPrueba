<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehieloevaluacionModel extends Model
{
    protected $table = 'reportehieloevaluacion';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reportehieloarea_id'
		, 'reportehieloevaluacion_punto'
		, 'reportehieloevaluacion_fecha'
		, 'reportehieloevaluacion_ubicacion'
		, 'reportehieloevaluacion_suministro'
		, 'reportehieloevaluacion_tipouso'
		, 'reportehieloevaluacion_descripcionuso'
		, 'reportehieloevaluacion_condiciones'
		, 'reportehieloevaluacion_totalpersonas'
		, 'reportehieloevaluacion_geo'
		, 'reportehieloevaluacion_foliomuestra'
		, 'reportehieloevaluacion_tipoevaluacion'
	];
}
