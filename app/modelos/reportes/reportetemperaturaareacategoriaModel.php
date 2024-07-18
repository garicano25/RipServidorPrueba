<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportetemperaturaareacategoriaModel extends Model
{
    protected $table = 'reportetemperaturaareacategoria';
    protected $fillable = [
          'reportearea_id'
        , 'reportecategoria_id'
    ];
}
