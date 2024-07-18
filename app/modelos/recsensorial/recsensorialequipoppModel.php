<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialequipoppModel extends Model
{
	protected $table = 'recsensorialequipopp';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialcategoria_id',
		'catpartecuerpo_id',
		'catpartescuerpo_descripcion_id'
	];

	//=============== TABLAS ===============

	public function recsensorialcategoria()
	{
		return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class);
	}

	//=============== CATALOGOS ===============

	public function catpartecuerpo()
	{
		return $this->belongsTo(\App\modelos\recsensorial\catpartecuerpoModel::class);
	}
}
