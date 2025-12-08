<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catclasificacionriesgoModel extends Model
{
    protected $table = 'cat_clasificacionriesgoepp';
    protected $primaryKey = 'ID_CLASIFICACION_RIESGO';
    protected $fillable = [

        'CLASIFICACION_RIESGO',
        'ACTIVO'

    ];
}
