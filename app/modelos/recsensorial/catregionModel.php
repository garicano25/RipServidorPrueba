<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catregionModel extends Model
{
	protected $table = 'catregion';
	protected $fillable = [
		'catregion_nombre',
		'catregion_activo'
	];

	//============ RELACION CON TABLAS ==========

	public function recsensorial()
	{
		return $this->hasMany(\App\modelos\recsensorial\recsensorialModel::class);
	}
}
