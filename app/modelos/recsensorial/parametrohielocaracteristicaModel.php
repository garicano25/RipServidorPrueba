<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrohielocaracteristicaModel extends Model
{
    protected $table = 'parametrohielocaracteristica';
	protected $fillable = [
		'parametrohielo_id',
		'catparametrohielocaracteristica_id'
	];


	//=============================================================


	public function catparametrohielocaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catparametrohielocaracteristicaModel::class, 'catparametrohielocaracteristica_id');
    }
}
