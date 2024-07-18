<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportevibracioncatalogoModel extends Model
{
    protected $table = 'reportevibracioncatalogo';
    protected $fillable = [
          'reportevibracioncatalogo_catregion_activo'
        , 'reportevibracioncatalogo_catsubdireccion_activo'
        , 'reportevibracioncatalogo_catgerencia_activo'
        , 'reportevibracioncatalogo_catactivo_activo'
        , 'reportevibracioncatalogo_introduccion'
        , 'reportevibracioncatalogo_objetivogeneral'
        , 'reportevibracioncatalogo_objetivoespecifico'
        , 'reportevibracioncatalogo_metodologia_4_1'
        , 'reportevibracioncatalogo_ubicacioninstalacion'
        , 'reportevibracioncatalogo_conclusion'
    ];
}
