<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeicatalogoModel extends Model
{
    //
    protected $table = 'reportebeicatalogo';
    protected $fillable = [
        'reportebeicatalogo_catregion_activo',
        'reportebeicatalogo_catsubdireccion_activo',
        'reportebeicatalogo_catgerencia_activo',
        'reportebeicatalogo_catactivo_activo',
        'reportebeicatalogo_introduccion',
        'reportebeicatalogo_objetivogeneral',
        'reportebeicatalogo_objetivoespecifico',
        'reportebeicatalogo_metodologia_4_1',
        'reportebeicatalogo_metodologia_4_2',
        'reportebeicatalogo_metodologia_4_2_1',
        'reportebeicatalogo_metodologia_4_2_2',
        'reportebeicatalogo_metodologia_4_2_3',
        'reportebeicatalogo_metodologia_4_2_4',
        'reportebeicatalogo_ubicacioninstalacion',
        'reportebeicatalogo_criterioseleccion',
        'reportebeicatalogo_conclusion'
    ];
}
