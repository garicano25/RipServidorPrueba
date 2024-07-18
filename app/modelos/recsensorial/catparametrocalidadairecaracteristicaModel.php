<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catparametrocalidadairecaracteristicaModel extends Model
{
    protected $table = 'catparametrocalidadairecaracteristica';
	protected $fillable = [
		  'catparametrocalidadairecaracteristica_caracteristica'
		, 'catparametrocalidadairecaracteristica_metodo'
		, 'catparametrocalidadairecaracteristica_limite'
		, 'catparametrocalidadairecaracteristica_activo'
	];
}
