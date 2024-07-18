<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportetemperaturaModel extends Model
{
    protected $table = 'reportetemperatura';
    protected $fillable = [
          'proyecto_id'
        , 'catactivo_id'
        , 'reportetemperatura_fecha'
        , 'reportetemperatura_instalacion'
        , 'reportetemperatura_catregion_activo'
        , 'reportetemperatura_catsubdireccion_activo'
        , 'reportetemperatura_catgerencia_activo'
        , 'reportetemperatura_catactivo_activo'
        , 'reportetemperatura_introduccion'
        , 'reportetemperatura_objetivogeneral'
        , 'reportetemperatura_objetivoespecifico'
        , 'reportetemperatura_metodologia_4_1'
        , 'reportetemperatura_ubicacioninstalacion'
        , 'reportetemperatura_ubicacionfoto'
        , 'reportetemperatura_procesoinstalacion'
        , 'reportetemperatura_actividadprincipal'
        , 'reportetemperatura_conclusion'
        , 'reportetemperatura_responsable1'
        , 'reportetemperatura_responsable1cargo'
        , 'reportetemperatura_responsable1documento'
        , 'reportetemperatura_responsable2'
        , 'reportetemperatura_responsable2cargo'
        , 'reportetemperatura_responsable2documento'
    ];
}
