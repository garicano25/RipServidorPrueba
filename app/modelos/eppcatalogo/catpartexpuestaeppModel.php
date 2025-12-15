<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catpartexpuestaeppModel extends Model
{
    protected $table = 'cat_partexespuestaepp';
    protected $primaryKey = 'ID_PARTE_EXPUESTO';
    protected $fillable = [
        'NOMBRE_PARTE',
        'ACTIVO'

    ];
}
