<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrocalidadaireModel extends Model
{
    protected $table = 'parametrocalidadaire';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'parametrocalidadaire_ubicacion',
		'parametrocalidadaire_puntos'
	];

	//=============== SINCRONIZACION CON TABLA ===================

    public function airecaracteristicas()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel::class, 'parametrocalidadairecaracteristica', 'parametrocalidadaire_id', 'catparametrocalidadairecaracteristica_id');
    }

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }

    public function caracteristicas()
    {
        return $this->hasMany(\App\modelos\recsensorial\parametrocalidadairecaracteristicasModel::class, 'parametrocalidadaire_id');
    }
}
