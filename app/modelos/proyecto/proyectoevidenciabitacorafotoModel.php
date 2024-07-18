<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciabitacorafotoModel extends Model
{
    protected $table = 'proyectoevidenciabitacorafoto';
	protected $fillable = [
		  'proyectoevidenciabitacora_id'
		, 'proyectoevidenciabitacorafoto_ruta'
	];
}
