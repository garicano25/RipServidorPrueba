<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoequipohistorialModel extends Model
{
	protected $table = 'proyectoequiposhistorial';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'proyectoequipo_revision',
		'equipo_id'
	];
}
