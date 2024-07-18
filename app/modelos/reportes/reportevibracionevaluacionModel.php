<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracionevaluacionModel extends Model
{
    protected $table = 'reportevibracionevaluacion';
    protected $fillable = [
          'proyecto_id'
        , 'reportearea_id'
        , 'reportevibracionevaluacion_puntoevaluacion'
        , 'reportecategoria_id'
        , 'reportevibracionevaluacion_nombre'
        , 'reportevibracionevaluacion_ficha'
        , 'reportevibracionevaluacion_punto'
        , 'reportevibracionevaluacion_tipoevaluacion'
        , 'reportevibracionevaluacion_tiempoexposicion'
        , 'reportevibracionevaluacion_numeromediciones'
        , 'reportevibracionevaluacion_promedio'
        , 'reportevibracionevaluacion_valormaximo'
        , 'reportevibracionevaluacion_fecha'
    ];
}
