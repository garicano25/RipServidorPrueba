<?php

namespace App\modelos\usuario;

use Illuminate\Database\Eloquent\Model;

class rolModel extends Model
{
    //
    protected $table = 'rol';
	protected $fillable = [
		'rol_Modulo',
		'rol_Nombre',
		'rol_Descripcion',
		'rol_Orden',
		'ACTIVO'
	];

	//============ RELACION CON TABLAS ==========

	// public function usuario()
	// {
	// 	return $this->hasMany(\App\modelos\usuario\recsensorialModel::class);
	// }
}
