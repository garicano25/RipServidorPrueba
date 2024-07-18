<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrohieloModel extends Model
{
    protected $table = 'parametrohielo';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'parametrohielo_ubicacion',
		'catparametrohielocaracteristica_id',
		'parametrohielo_puntos'
	];

	//=============== SINCRONIZACION CON TABLA ===================

    public function parametrohielocaracteristicasincronizacion()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\catparametrohielocaracteristicaModel::class, 'parametrohielocaracteristica', 'parametrohielo_id', 'catparametrohielocaracteristica_id');
    }


	//=============================================================


	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }


    public function parametrohielocaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\parametrohielocaracteristicaModel::class);
    }
}
