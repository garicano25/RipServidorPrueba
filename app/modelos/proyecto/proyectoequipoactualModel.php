<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoequipoactualModel extends Model
{
	protected $table = 'proyectoequiposactual';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'equipo_id'
	];
}
