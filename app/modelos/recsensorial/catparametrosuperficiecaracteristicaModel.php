<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catparametrosuperficiecaracteristicaModel extends Model
{
    protected $table = 'catparametrosuperficiecaracteristica';
	protected $fillable = [
		'catparametrosuperficiecaracteristica_caracteristica',
		'catparametrosuperficiecaracteristica_activo'
	];
}
