<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportetemperaturaareamaquinariaModel extends Model
{
    protected $table = 'reportetemperaturamaquinaria';
    protected $fillable = [
          'reportearea_id'
        , 'reportetemperaturamaquinaria_nombre'
        , 'reportetemperaturamaquinaria_cantidad'
    ];
}
