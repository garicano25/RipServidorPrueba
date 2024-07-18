<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialareapruebasModel extends Model
{
	protected $table = 'recsensorialareapruebas';
    protected $fillable = [
		'recsensorialarea_id',
		'catprueba_id'
    ];

    public function catprueba()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class, 'catprueba_id');
    }
}
