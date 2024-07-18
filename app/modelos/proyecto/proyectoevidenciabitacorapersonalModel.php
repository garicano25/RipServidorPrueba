<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciabitacorapersonalModel extends Model
{
    protected $table = 'proyectoevidenciabitacorapersonal';
	protected $fillable = [
		  'proyectoevidenciabitacora_id'
		, 'signatario_id'
		, 'signatario_nombre'
		, 'signatario_observacion'
		, 'agente_id'
		, 'agente_nombre'
		, 'agente_puntos'
	];
}
