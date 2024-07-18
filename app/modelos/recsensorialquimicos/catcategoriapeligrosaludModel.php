<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catcategoriapeligrosaludModel extends Model
{
    protected $table = 'catcategoriapeligrosalud';
	protected $fillable = [
		'catcategoriapeligrosalud_codigo',
		'catcategoriapeligrosalud_descripcion',
		'catcategoriapeligrosalud_activo',
		'CLASIFICACION_RIESGO_CATEGORIA'
	];
}
