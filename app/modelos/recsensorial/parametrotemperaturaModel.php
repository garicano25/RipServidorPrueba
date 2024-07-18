<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrotemperaturaModel extends Model
{
    protected $table = 'parametrotemperatura';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'recsensorialcategoria_id',
		'parametrotemperatura_puntote',
		'parametrotemperatura_puntota'
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
