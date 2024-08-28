<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosproyectoModel extends Model
{
	protected $table = 'reportequimicosproyecto';
	protected $fillable = [
		'proyecto_id',
		'registro_id',
		'reportequimicosproyecto_parametro',
		'cantidad'
	];
}
