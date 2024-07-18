<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catparametrohielocaracteristicaModel extends Model
{
    protected $table = 'catparametrohielocaracteristica';
	protected $fillable = [
		  'catparametrohielocaracteristica_tipo'
		, 'catparametrohielocaracteristica_caracteristica'
		, 'catparametrohielocaracteristica_unidadmedida'
		, 'catparametrohielocaracteristica_concentracionpermisible'
		, 'catparametrohielocaracteristica_activo'
	];
}
