<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catvolatilidadModel extends Model
{
    protected $table = 'catvolatilidad';
	protected $fillable = [
		'catvolatilidad_tipo',
		'catvolatilidad_ponderacion',
		'catvolatilidad_activo'
	];
}
