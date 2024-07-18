<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciaplanoModel extends Model
{
    protected $table = 'proyectoevidenciaplano';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'agente_id',
		'agente_nombre',
		'proyectoevidenciaplano_carpeta',
		'catreportequimicospartidas_id',
		'proyectoevidenciaplano_archivo'
	];
}
