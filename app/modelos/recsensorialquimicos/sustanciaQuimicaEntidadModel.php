<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class sustanciaQuimicaEntidadModel extends Model
{
    protected $primaryKey = 'ID_SUSTANCIA_QUIMICA_ENTIDAD';
    protected $table = 'sustanciaQuimicaEntidad';
    protected $fillable = [
        'SUSTANCIA_QUIMICA_ID',
        'CONNOTACION',
        'VLE_PPT',
        'VLE_CT_P',
        'ENTIDAD_ID',
        'DESCRIPCION_NORMATIVA',
        'JSON_BEIS',
        'NOTA_SUSTANCIA_ENTIDAD',
        'TIENE_BEIS',
        'ACTIVO'
    ];


    protected $casts = [
        'CONNOTACION' => 'array'
    ];
}
