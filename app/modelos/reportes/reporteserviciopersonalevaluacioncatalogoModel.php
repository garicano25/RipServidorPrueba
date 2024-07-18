<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalevaluacioncatalogoModel extends Model
{
    protected $table = 'reporteserviciopersonalevaluacioncatalogo';
    protected $fillable = [
          'reporteserviciopersonalevaluacioncatalogo_titulo'
        , 'reporteserviciopersonalevaluacioncatalogo_descripcion'
    ];
}
