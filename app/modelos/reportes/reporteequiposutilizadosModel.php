<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteequiposutilizadosModel extends Model
{
    protected $table = 'reporteequiposutilizados';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'registro_id'
		, 'equipo_id'
		, 'reporteequiposutilizados_cartacalibracion'
	];
}
