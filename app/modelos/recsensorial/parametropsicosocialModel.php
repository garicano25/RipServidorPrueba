<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametropsicosocialModel extends Model
{
    protected $table = 'parametropsicosocial';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'recsensorialcategoria_id',
		'parametropsicosocial_nopersonas'
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
