<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroalimentoModel extends Model
{
    protected $table = 'parametroalimento';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'parametroalimento_ubicacion',
		'parametroalimento_caracteristica',
		'parametroalimento_puntos'
	];


	//=============== SINCRONIZACION CON TABLA ===================

    public function parametroalimentocaracteristicasincronizacion()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\catparametroalimentocaracteristicaModel::class, 'parametroalimentocaracteristica', 'parametroalimento_id', 'catparametroalimentocaracteristica_id');
    }

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }


    public function parametroalimentocaracteristica()
    {
        return $this->hasMany(\App\modelos\recsensorial\parametroalimentocaracteristicaModel::class, 'parametroalimento_id');
    }
}
