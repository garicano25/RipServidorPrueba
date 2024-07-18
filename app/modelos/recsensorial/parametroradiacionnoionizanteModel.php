<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroradiacionnoionizanteModel extends Model
{
    protected $table = 'parametroradiacionnoionizante';
	protected $fillable = [
		'recsensorial_id',
		'recsensorialarea_id',
		'recsensorialcategoria_id',
		'parametroradiacionnoionizante_fuente',
		'parametroradiacionnoionizante_puntos'
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
