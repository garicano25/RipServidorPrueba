<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialevidenciasModel extends Model
{
    protected $table = 'recsensorialevidencias';
    protected $fillable = [
          'recsensorial_id'
        , 'cat_prueba_id'
        , 'recsensorialarea_id'
        , 'recsensorialevidencias_tipo'
        , 'recsensorialevidencias_descripcion'
        , 'recsensorialevidencias_foto'
    ];
}
