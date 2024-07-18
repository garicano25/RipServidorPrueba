<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_pruebanormaModel extends Model
{
  protected $table = 'cat_pruebanorma';
  // protected $primaryKey = 'id';
  protected $fillable = [
    'cat_prueba_id',
    'catpruebanorma_tipo',
    'catpruebanorma_numero',
    'catpruebanorma_descripcion'
  ];
}
