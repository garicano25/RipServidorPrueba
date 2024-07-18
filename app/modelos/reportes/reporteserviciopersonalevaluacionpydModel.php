<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalevaluacionpydModel extends Model
{
    protected $table = 'reporteserviciopersonalevaluacionpyd';
    protected $fillable = [
          'proyecto_id'
        , 'reporteserviciopersonalevaluacionpyd_areainstalacion'
        , 'reporteserviciopersonalevaluacionpyd_tipologia'
        , 'reporteserviciopersonalevaluacionpyd_personas'
        , 'reporteserviciopersonalevaluacionpyd_m2'
        , 'reporteserviciopersonalevaluacionpyd_escusados'
        , 'reporteserviciopersonalevaluacionpyd_lavabos'
        , 'reporteserviciopersonalevaluacionpyd_mingitorios'
        , 'reporteserviciopersonalevaluacionpyd_Regaderas'
        , 'reporteserviciopersonalevaluacionpyd_notap'
        , 'reporteserviciopersonalevaluacionpyd_notad'
    ];
}
