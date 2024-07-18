<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectosignatarioactualModel extends Model
{
    protected $table = 'proyectosignatariosactual';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'signatario_id'
	];
}
