<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroergonomiaareaModel extends Model
{
    protected $table = 'parametroergonomiaarea';
    protected $fillable = [
		'parametroergonomia_id',
		'recsensorialarea_id'
    ];

    //=============== RELACION CATALOGO AREA ===================

    public function recsensorialarea()
    {
    	return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }
}
