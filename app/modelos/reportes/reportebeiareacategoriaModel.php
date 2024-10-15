<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeiareacategoriaModel extends Model
{
    protected $table = 'reportebeiareacategoria';
    protected $fillable = [
        'reportebeiarea_id',
        'reportebeicategoria_id',
        'reportebeiareacategoria_poe',
        'reportebeiareacategoria_total',
        'reportebeiareacategoria_geo',
        'reportebeiareacategoria_actividades',
    ];
}
