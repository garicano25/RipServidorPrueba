<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciafotoModel extends Model
{
	protected $table = 'proyectoevidenciafoto';
	protected $fillable = [
		'proyecto_id',
		'proveedor_id',
		'agente_id',
		'agente_nombre',
		'proyectoevidenciafoto_carpeta',
		'proyectoevidenciafoto_nopunto',
		'proyectoevidenciafoto_archivo',
		'proyectoevidenciafoto_descripcion'
	];
}
