<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectopuntosrealesModel extends Model
{
    protected $table = 'proyectopuntosreales';
	protected $fillable = [
		  'proyecto_id'
		, 'proyectoproveedores_id'
		, 'proyectopuntosreales_puntos'
		, 'proyectopuntosreales_observacion'
	];
}
