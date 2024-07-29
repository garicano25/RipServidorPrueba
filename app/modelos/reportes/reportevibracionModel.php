<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracionModel extends Model
{
    protected $table = 'reportevibracion';
    protected $fillable = [
          'proyecto_id'
        , 'catactivo_id'
        , 'reportevibracion_fecha'
        ,'reporte_mes'
        , 'reportevibracion_instalacion'
        , 'reportevibracion_catregion_activo'
        , 'reportevibracion_catsubdireccion_activo'
        , 'reportevibracion_catgerencia_activo'
        , 'reportevibracion_catactivo_activo'
        , 'reportevibracion_alcanceinforme'
        , 'reportevibracion_introduccion'
        , 'reportevibracion_objetivogeneral'
        , 'reportevibracion_objetivoespecifico'
        , 'reportevibracion_metodologia_4_1'
        , 'reportevibracion_ubicacioninstalacion'
        , 'reportevibracion_ubicacionfoto'
        , 'reportevibracion_procesoinstalacion'
        , 'reportevibracion_actividadprincipal'
        , 'reportevibracion_conclusion'
        , 'reportevibracion_responsable1'
        , 'reportevibracion_responsable1cargo'
        , 'reportevibracion_responsable1documento'
        , 'reportevibracion_responsable2'
        , 'reportevibracion_responsable2cargo'
        , 'reportevibracion_responsable2documento'
    ];
}
