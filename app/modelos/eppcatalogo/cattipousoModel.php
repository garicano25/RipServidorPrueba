<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class cattipousoModel extends Model
{
    protected $table = 'cat_tipousoepp';
    protected $primaryKey = 'ID_TIPO_USO';
    protected $fillable = [
        'TIPO_USO',
        'ACTIVO'

    ];
}
