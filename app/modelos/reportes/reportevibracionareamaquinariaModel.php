<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracionareamaquinariaModel extends Model
{
    protected $table = 'reportevibracionmaquinaria';
    protected $fillable = [
          'reportearea_id'
        , 'reportevibracionmaquinaria_nombre'
        , 'reportevibracionmaquinaria_cantidad'
    ];
}
