<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracionevaluaciondatosModel extends Model
{
    protected $table = 'reportevibracionevaluaciondatos';
    protected $fillable = [
          'reportevibracionevaluacion_id'
        , 'reportevibracionevaluaciondatos_frecuencia'
        , 'reportevibracionevaluaciondatos_az1'
        , 'reportevibracionevaluaciondatos_az2'
        , 'reportevibracionevaluaciondatos_az3'
        , 'reportevibracionevaluaciondatos_azlimite'
        , 'reportevibracionevaluaciondatos_ax1'
        , 'reportevibracionevaluaciondatos_ax2'
        , 'reportevibracionevaluaciondatos_ax3'
        , 'reportevibracionevaluaciondatos_ay1'
        , 'reportevibracionevaluaciondatos_ay2'
        , 'reportevibracionevaluaciondatos_ay3'
        , 'reportevibracionevaluaciondatos_axylimite'
    ];
}
