<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catgerenciaModel extends Model
{
	protected $table = 'catgerencia';
	protected $fillable = [
		'catgerencia_siglas',
		'catgerencia_nombre',
		'catgerencia_activo'
	];

	//============ RELACION CON TABLAS ==========

	public function recsensorial()
	{
		return $this->hasMany(\App\modelos\recsensorial\recsensorialModel::class);
	}
}
