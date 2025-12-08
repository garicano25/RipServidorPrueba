<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class cattallaseppModel extends Model
{
    protected $table = 'cat_tallasepp';
    protected $primaryKey = 'ID_TALLA';
    protected $fillable = [
        'NOMBRE_TALLA',
        'ACTIVO'

    ];
}
