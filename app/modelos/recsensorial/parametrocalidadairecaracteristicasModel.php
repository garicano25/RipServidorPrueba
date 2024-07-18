<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrocalidadairecaracteristicasModel extends Model
{
    protected $table = 'parametrocalidadairecaracteristica';
	protected $fillable = [
		'parametrocalidadaire_id',
		'catparametrocalidadairecaracteristica_id'
	];

	//=============================================================

	public function catalogoairecaracteristicas()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel::class, 'catparametrocalidadairecaracteristica_id');
    }
}
