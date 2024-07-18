<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciabitacoraModel extends Model
{
    protected $table = 'proyectoevidenciabitacora';
	protected $fillable = [
		  'proyecto_id'
		, 'usuario_id'
		, 'proyectoevidenciabitacora_fecha'
		, 'proyectoevidenciabitacora_observacion'
	];
}
