<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaireevaluacionModel   extends Model
{
    protected $table = 'reporteaireevaluacion';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteairearea_id'
		, 'reporteaireevaluacion_punto'
		, 'reporteaireevaluacion_ubicacion'
		, 'reporteaireevaluacion_ct'
		, 'reporteaireevaluacion_ctma'
		, 'reporteaireevaluacion_hongos'
		, 'reporteaireevaluacion_levaduras'
		, 'reporteaireevaluacion_temperatura'
		, 'reporteaireevaluacion_velocidad'
		, 'reporteaireevaluacion_velocidadlimite'
		, 'reporteaireevaluacion_humedad'
		, 'reporteaireevaluacion_co'
		, 'reporteaireevaluacion_co2'
		, 'reporteaireevaluacion_so2'
	];
}
