<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteplanoscarpetasModel extends Model
{
    protected $table = 'reporteplanoscarpetas';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'registro_id'
		, 'reporteplanoscarpetas_nombre'
	];
}