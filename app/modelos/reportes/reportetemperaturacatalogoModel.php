<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportetemperaturacatalogoModel extends Model
{
    protected $table = 'reportetemperaturacatalogo';
    protected $fillable = [
          'reportetemperaturacatalogo_catregion_activo'
        , 'reportetemperaturacatalogo_catsubdireccion_activo'
        , 'reportetemperaturacatalogo_catgerencia_activo'
        , 'reportetemperaturacatalogo_catactivo_activo'
        , 'reportetemperaturacatalogo_introduccion'
        , 'reportetemperaturacatalogo_objetivogeneral'
        , 'reportetemperaturacatalogo_objetivoespecifico'
        , 'reportetemperaturacatalogo_metodologia_4_1'
        , 'reportetemperaturacatalogo_ubicacioninstalacion'
        , 'reportetemperaturacatalogo_conclusion'
    ];
}
