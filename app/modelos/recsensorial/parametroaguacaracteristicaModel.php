<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroaguacaracteristicaModel extends Model
{
    protected $table = 'parametroaguacaracteristica';
	protected $fillable = [
		'parametroagua_id',
		'catparametroaguahielocaracteristica_id'
	];


	//=============================================================


	public function catparametroaguahielocaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catparametroaguahielocaracteristicaModel::class, 'catparametroaguahielocaracteristica_id');
    }
}
