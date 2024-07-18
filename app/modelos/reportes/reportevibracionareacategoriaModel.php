<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracionareacategoriaModel extends Model
{
    protected $table = 'reportevibracionareacategoria';
    protected $fillable = [
          'reportearea_id'
        , 'reportecategoria_id'
    ];
}
