<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeicategoriaModel extends Model
{
    protected $table = 'reportebeicategoria';
    protected $fillable = [
        'proyecto_id',
        'registro_id',
        'recsensorialcategoria_id',
        'reportebeicategoria_nombre',
        'reportebeicategoria_total',
        'reportebeicategoria_horas'
    ];
}
