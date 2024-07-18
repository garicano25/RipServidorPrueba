<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalModel extends Model
{
    protected $table = 'reporteserviciopersonal';
    protected $fillable = [
          'proyecto_id'
        , 'catactivo_id'
        , 'reporteserviciopersonal_fecha'
        , 'reporteserviciopersonal_instalacion'
        , 'reporteserviciopersonal_catregion_activo'
        , 'reporteserviciopersonal_catsubdireccion_activo'
        , 'reporteserviciopersonal_catgerencia_activo'
        , 'reporteserviciopersonal_catactivo_activo'
        , 'reporteserviciopersonal_alcanceinforme'
        , 'reporteserviciopersonal_introduccion'
        , 'reporteserviciopersonal_objetivogeneral'
        , 'reporteserviciopersonal_objetivoespecifico'
        , 'reporteserviciopersonal_ubicacioninstalacion'
        , 'reporteserviciopersonal_ubicacionfoto'
        , 'reporteserviciopersonal_metodologia_4'
        , 'reporteserviciopersonal_metodologia_8_3'
        , 'reporteserviciopersonal_metodologia_8_4'
        , 'reporteserviciopersonal_procesoinstalacion'
        , 'reporteserviciopersonal_conclusion'
        , 'reporteserviciopersonal_recomendaciones'
        , 'reporteserviciopersonal_responsable1'
        , 'reporteserviciopersonal_responsable1cargo'
        , 'reporteserviciopersonal_responsable1documento'
        , 'reporteserviciopersonal_responsable2'
        , 'reporteserviciopersonal_responsable2cargo'
        , 'reporteserviciopersonal_responsable2documento'
    ];
}
