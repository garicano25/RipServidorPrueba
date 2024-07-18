<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoprorrogasModel extends Model
{
	protected $table = 'proyectoprorrogas';
	protected $fillable = [
		  'proyecto_id'
		, 'proyectoprorrogas_fechainicio'
		, 'proyectoprorrogas_fechafin'
		, 'proyectoprorrogas_dias'
	];
}
