<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrosuperficieModel extends Model
{
    protected $table = 'parametrosuperficie';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'parametrosuperficie_ubicacion',
		'parametrosuperficie_caracteristica',
		'parametrosuperficie_puntos',
		'parametrosuperficie_observacion'
	];

	//=============== SINCRONIZACION CON TABLA ===================

    public function parametrosuperficiecaracteristicasincronizacion()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\catparametrosuperficiecaracteristicaModel::class, 'parametrosuperficiecaracteristica', 'parametrosuperficie_id', 'catparametrosuperficiecaracteristica_id');
    }

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }

    public function parametrosuperficiecaracteristica()
    {
        return $this->hasMany(\App\modelos\recsensorial\parametrosuperficiecaracteristicaModel::class, 'parametrosuperficie_id');
    }
}
