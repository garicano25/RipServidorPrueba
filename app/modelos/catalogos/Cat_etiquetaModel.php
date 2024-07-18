<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_etiquetaModel extends Model
{
    protected $primaryKey = 'ID_ETIQUETA';
    protected $table = 'cat_etiquetas';
    protected $fillable = [
        'NOMBRE_ETIQUETA',
        'DESCRIPCION_ETIQUETA',
        'TIPO_ETIQUETA',
        'ACTIVO'
    ];
}
