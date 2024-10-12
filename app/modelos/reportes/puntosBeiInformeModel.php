<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class puntosBeiInformeModel extends Model
{
    //
    protected $primaryKey = 'ID_BEI_INFORME';
    protected $table = 'puntosBeiInforme';
    protected $fillable = [
        'PROYECTO_ID',
        'RECSENSORIAL_ID',
        'NUM_PUNTO_BEI',
        'BEI_ID',
        'AREA_ID',
        'CATEGORIA_ID',
        'NOMBRE_BEI',
        'GENERO_BEI',
        'FICHA_BEI',
        'EDAD_BEI',
        'ANTIGUEDAD_BEI',
        'MUESTRA_BEI',
        'UNIDAD_MEDIDA_BEI',
        'RESULTADO_BEI',
        'REFERENCIA_BEI'
    ];
}
