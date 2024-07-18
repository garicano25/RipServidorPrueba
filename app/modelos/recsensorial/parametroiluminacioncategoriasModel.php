<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroiluminacioncategoriasModel extends Model
{
    protected $table = 'parametroiluminacioncategorias';
    protected $fillable = [
		'recsensorialarea_id',
		'recsensorialcategoria_id'
    ];

    public function categorias()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialcategoria_id');
    }
}
