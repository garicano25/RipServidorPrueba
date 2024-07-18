<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroprecionesambientalesModel extends Model
{
    protected $table = 'parametroprecionesambientales';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'recsensorialcategoria_id',
		'parametroprecionesambientales_contaminante',
		'parametroprecionesambientales_puntos'
	];

	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }

    public function recsensorialcategoria()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialcategoria_id');
    }
}
