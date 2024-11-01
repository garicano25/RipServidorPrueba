<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportealimentoscatalogoModel extends Model
{
    //
    protected $table = 'reportealimentoscatalogo';
    protected $fillable = [
        'reportealimentoscatalogo_catregion_activo',
        'reportealimentoscatalogo_catsubdireccion_activo',
        'reportealimentoscatalogo_catgerencia_activo',
        'reportealimentoscatalogo_catactivo_activo',
        'reportealimentoscatalogo_introduccion',
        'reportealimentoscatalogo_objetivogeneral',
        'reportealimentoscatalogo_objetivoespecifico',
        'reportealimentoscatalogo_metodologia_4_1',
        'reportealimentoscatalogo_metodologia_4_2',
        'reportealimentoscatalogo_metodologia_5_1',
        'reportealimentoscatalogo_metodologia_5_2',
        'reportealimentoscatalogo_ubicacioninstalacion',
        'reportealimentoscatalogo_conclusion'
    ];
}
