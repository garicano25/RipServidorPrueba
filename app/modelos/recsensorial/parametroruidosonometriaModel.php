<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroruidosonometriaModel extends Model
{
    protected $table = 'parametroruidosonometria';
	protected $fillable = [
		  'recsensorial_id'
		, 'recsensorialarea_id'
		, 'parametroruidosonometria_medmax'
		, 'parametroruidosonometria_medmin'
		, 'parametroruidosonometria_med1'
		, 'parametroruidosonometria_med2'
		, 'parametroruidosonometria_med3'
		, 'parametroruidosonometria_med4'
		, 'parametroruidosonometria_med5'
		, 'parametroruidosonometria_med6'
		, 'parametroruidosonometria_med7'
		, 'parametroruidosonometria_med8'
		, 'parametroruidosonometria_med9'
		, 'parametroruidosonometria_med10'
		, 'parametroruidosonometria_puntos'
	];


	//=============================================================

	public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class, 'recsensorialarea_id');
    }

    //=============================================================

    public function recsensorialareacategorias()
    {
        return $this->hasMany(\App\modelos\recsensorial\recsensorialareacategoriasModel::class, 'recsensorialarea_id');
    }
}