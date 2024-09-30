<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class evaluacionBeisModel extends Model
{
    //Generacion de la tabla
    protected $primaryKey = 'ID_RECSENSORIAL_BEI';
    protected $table = 'evaluacionBeis';
    protected $fillable = [
        'RECONOCIMIENTO_ID',
        'SUSTANCIA_QUIMICA_ID',
        'AREA_ID',
        'CATEGORIA_ID',
        'DETERMINANTE_ID',
        'NOMBRE_PERSONA',
        'FECHA_NACIMIENTO',
        'EDAD',
        'ANTIGUEDAD_LABORAL',
        'TIEMPO_MUESTREO',
        'NUMERO_MUESTRA'
    ];

}
