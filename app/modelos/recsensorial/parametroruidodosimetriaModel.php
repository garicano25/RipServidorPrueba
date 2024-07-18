<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroruidodosimetriaModel extends Model
{
	protected $table = 'parametroruidodosimetria';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialcategoria_id',
		'parametroruidodosimetria_dosis'
	];

	//=============================================================

	public function recsensorialcategoria()
	{
		return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialcategoria_id');
	}
}
