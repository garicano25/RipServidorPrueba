<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catparametroalimentocaracteristicaModel extends Model
{
    protected $table = 'catparametroalimentocaracteristica';
	protected $fillable = [
		'catparametroalimentocaracteristica_caracteristica',
		'catparametroalimentocaracteristica_activo'
	];
}
