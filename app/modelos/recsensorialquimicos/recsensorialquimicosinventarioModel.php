<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class recsensorialquimicosinventarioModel extends Model
{
    protected $table = 'recsensorialquimicosinventario';
	protected $fillable = [
        'recsensorial_id',
        'recsensorialarea_id',
        'catsustancia_id',
        'recsensorialquimicosinventario_cantidad',
        'catunidadmedidasustacia_id',
        'recsensorialcategoria_id',
        'recsensorialcategoria_tiempoexpo',
        'recsensorialcategoria_frecuenciaexpo'
	];

	//=============== RELACION A CATALOGOS ===================

    public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class);
    }

    public function recsensorialcategoria()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class);
    }

    public function catunidadmedidasustacia()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catunidadmedidasustaciaModel::class);
    }

    public function catsustancia()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catsustanciaModel::class);
    }
}