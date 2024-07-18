<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catparametroaguacaracteristicaModel extends Model
{
    protected $table = 'catparametroaguacaracteristica';
	protected $fillable = [
		  'catparametroaguacaracteristica_tipo'
		, 'catparametroaguacaracteristica_caracteristica'
		, 'catparametroaguacaracteristica_unidadmedida'
		, 'catparametroaguacaracteristica_concentracionpermisible'
		, 'catparametroaguacaracteristica_activo'
	];
}
