<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catactivoModel extends Model
{
	protected $table = 'catactivo';
	protected $fillable = [
		'catactivo_siglas',
		'catactivo_nombre',
		'catactivo_activo'
	];

	//============ RELACION CON TABLAS ==========

	public function recsensorial()
	{
		return $this->hasMany(\App\modelos\recsensorial\recsensorialModel::class);
	}
}
