<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportedefinicionesModel extends Model
{
	protected $table = 'reportedefiniciones';
	protected $fillable = [
		  'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reportedefiniciones_concepto'
		, 'reportedefiniciones_descripcion'
		, 'reportedefiniciones_fuente'
	];
}