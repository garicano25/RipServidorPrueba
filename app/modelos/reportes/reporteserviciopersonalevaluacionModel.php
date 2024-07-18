<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalevaluacionModel extends Model
{
    protected $table = 'reporteserviciopersonalevaluacion';
    protected $fillable = [
          'proyecto_id'
        , 'reporteserviciopersonalevaluacioncatalogo_id'
        , 'reporteserviciopersonalevaluacion_punto'
        , 'reporteserviciopersonalevaluacion_lugar'
        , 'reporteserviciopersonalevaluacion_procedimiento'
        , 'reporteserviciopersonalevaluacion_titulo'
        , 'reporteserviciopersonalevaluacion_descripcion'
        , 'reporteserviciopersonalevaluacion_observacion'
        , 'reporteserviciopersonalevaluacion_evidencia1'
        , 'reporteserviciopersonalevaluacion_evidencia2'
        , 'reporteserviciopersonalevaluacion_cumplimiento'
    ];
}
