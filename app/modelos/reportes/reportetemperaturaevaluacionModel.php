<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportetemperaturaevaluacionModel extends Model
{
    protected $table = 'reportetemperaturaevaluacion';
    protected $fillable = [
        'proyecto_id'
      , 'reportearea_id'
      , 'reportecategoria_id'
      , 'reportetemperaturaevaluacion_trabajador'
      , 'reportetemperaturaevaluacion_ficha'
      , 'reportetemperaturaevaluacion_puesto'
      , 'reportetemperaturaevaluacion_tiempo'
      , 'reportetemperaturaevaluacion_ciclos'
      , 'reportetemperaturaevaluacion_punto'
      , 'reportetemperaturaevaluacion_regimen'
      , 'reportetemperaturaevaluacion_porcentaje'
      , 'reportetemperaturaevaluacion_I'
      , 'reportetemperaturaevaluacion_II'
      , 'reportetemperaturaevaluacion_III'
      , 'reportetemperaturaevaluacion_LMPE'
    ];
}
