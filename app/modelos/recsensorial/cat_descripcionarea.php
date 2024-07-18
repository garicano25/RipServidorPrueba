<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class cat_descripcionarea extends Model
{
    protected $primaryKey = 'ID_DESCRIPCION_AREA';
    protected $table = 'cat_descripcionarea';
    protected $fillable = [
        'DESCRIPCION',
        'ACTIVO'
    ];

}
