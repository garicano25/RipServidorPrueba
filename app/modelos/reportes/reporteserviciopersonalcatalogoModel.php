<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalcatalogoModel extends Model
{
    protected $table = 'reporteserviciopersonalcatalogo';
    protected $fillable = [
          'reporteserviciopersonalcatalogo_catregion_activo'
        , 'reporteserviciopersonalcatalogo_catsubdireccion_activo'
        , 'reporteserviciopersonalcatalogo_catgerencia_activo'
        , 'reporteserviciopersonalcatalogo_catactivo_activo'
        , 'reporteserviciopersonalcatalogo_introduccion'
        , 'reporteserviciopersonalcatalogo_objetivogeneral'
        , 'reporteserviciopersonalcatalogo_objetivoespecifico'
        , 'reporteserviciopersonalcatalogo_ubicacioninstalacion'
        , 'reporteserviciopersonalcatalogo_metodologia_4'
        , 'reporteserviciopersonalcatalogo_metodologia_8_3'
        , 'reporteserviciopersonalcatalogo_metodologia_8_4'
        , 'reporteserviciopersonalcatalogo_conclusion'
        , 'reporteserviciopersonalcatalogo_recomendaciones'
    ];
}
