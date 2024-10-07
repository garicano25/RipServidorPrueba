<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class sustanciasEntidadBeisModel extends Model
{
    protected $primaryKey = 'ID_BEI';
    protected $table = 'sustanciasEntidadBeis';
    protected $fillable = [
        'SUSTANCIA_QUIMICA_ID',
        'ENTIDAD_ID',
        'DETERMINANTE',
        'TIEMPO_MUESTREO',
        'BEI_DESCRIPCION',
        'NOTACION',
        'RECOMENDACION'
    ];


    protected $casts = [
        'NOTACION' => 'array'
    ];
}
