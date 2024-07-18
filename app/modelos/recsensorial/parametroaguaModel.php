<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroaguaModel extends Model
{
    protected $table = 'parametroagua';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'parametroagua_ubicacion',
		'parametroagua_tipouso',
		'catparametroaguacaracteristica_id',
		'parametroagua_puntos'
	];

	//=============== SINCRONIZACION CON TABLA ===================

    public function parametroaguacaracteristicasincronizacion()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\catparametroaguacaracteristicaModel::class, 'parametroaguacaracteristica', 'parametroagua_id', 'catparametroaguacaracteristica_id');
    }

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }


    public function parametroaguacaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\parametroaguacaracteristicaModel::class);
    }
}
