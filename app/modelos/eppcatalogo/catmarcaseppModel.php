<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catmarcaseppModel extends Model
{
    protected $table = 'cat_marcasepp';
    protected $primaryKey = 'ID_MARCAS_EPP';
    protected $fillable = [
        'NOMBRE_MARCA',
        'ACTIVO'

    ];
}
