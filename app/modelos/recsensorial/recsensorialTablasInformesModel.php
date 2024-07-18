<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialTablasInformesModel extends Model
{
    protected $primaryKey = 'ID_TABLA_INFORME';
    protected $table = 'recsensorial_tabla_informes';
    protected $fillable = [
        'RECONOCIMIENTO_ID',
        'CATEGORIA_ID',
        'PRODUCTO_ID',
        'SUSTANCIA_ID',
        'PPT_VIEJO',
        'CT_VIEJO',
        'PUNTOS_VIEJO',
        'PPT_NUEVO',
        'CT_NUEVO',
        'PUNTOS_NUEVO',
        'JUSTIFICACION',
        'ACTIVO',
    


    ];
}
