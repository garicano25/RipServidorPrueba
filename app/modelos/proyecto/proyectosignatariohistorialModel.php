<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectosignatariohistorialModel extends Model
{
    protected $table = 'proyectosignatarioshistorial';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'proyectosignatario_revision',
		'signatario_id'
	];
}
