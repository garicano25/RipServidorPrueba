<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialareacategoriasModel extends Model
{
  protected $table = 'recsensorialareacategorias';
  protected $fillable = [
    'recsensorialarea_id',
    'recsensorialcategoria_id',
    'recsensorialareacategorias_actividad',
    'recsensorialareacategorias_geh',
    'recsensorialareacategorias_total',
    'recsensorialareacategorias_tiempoexpo',
    'recsensorialareacategorias_frecuenciaexpo',
    'tiempoexpo_quimico',
    'frecuenciaexpo_quimico'
  ];

  public function categorias()
  {
    return $this->belongsTo(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialcategoria_id');
  }
}
