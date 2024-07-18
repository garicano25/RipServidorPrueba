<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportenotasModel extends Model
{
    protected $table = 'reportenotas';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'reportenotas_tipo'
		, 'reportenotas_descripcion'
	];
}