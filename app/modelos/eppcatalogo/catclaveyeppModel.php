<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catclaveyeppModel extends Model
{
    protected $table = 'cat_claveyepp';
    protected $primaryKey = 'ID_CLAVE_EPP';
    protected $fillable = [

        'REGION_ANATOMICA_ID',
        'CLAVE',
        'EPP',
        'TIPO_RIESGO',
        'ACTIVO',
        'NOTA_CLAVE'

    ];
}
