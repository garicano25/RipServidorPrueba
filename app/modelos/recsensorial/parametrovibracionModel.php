<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrovibracionModel extends Model
{
    protected $table = 'parametrovibracion';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'recsensorialcategoria_id',
		'parametrovibracion_puntovce',
		'parametrovibracion_puntoves'
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
