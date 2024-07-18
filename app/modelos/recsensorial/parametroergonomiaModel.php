<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroergonomiaModel extends Model
{
    protected $table = 'parametroergonomia';
    protected $fillable = [
		'recsensorial_id',
		'recsensorialcategoria_id',
		'parametroergonomia_movimientorepetitivo',
		'parametroergonomia_posturamantenida',
		'parametroergonomia_posturaforzada',
		'parametroergonomia_fuerza',
		'parametroergonomia_cargamanual'
	];

	//=============== SINCRONIZACION CON TABLAS ===================

    public function parametroergonomiaarea()
    {
        return $this->belongsToMany(\App\modelos\recsensorial\recsensorialareaModel::class, 'parametroergonomiaarea', 'parametroergonomia_id', 'recsensorialarea_id');
    }

    //=============== RELACION CATALOGOS ===================

    public function recsensorialcategoria()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialcategoria_id');
    }

    public function parametroergonomialistaareas()
    {
        return $this->hasMany(\App\modelos\recsensorial\parametroergonomiaareaModel::class, 'parametroergonomia_id');
    }
}
