<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroiluminacionModel extends Model
{
    protected $table = 'parametroiluminacion';
	protected $fillable = [
		  'recsensorial_id'
		, 'recsensorialarea_id'
		, 'parametroiluminacion_largo'
		, 'parametroiluminacion_ancho'
		, 'parametroiluminacion_alto'
		, 'parametroiluminacion_puntos'
	];

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }
}
