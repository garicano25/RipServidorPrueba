<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catregionanatomicaModel extends Model
{
    protected $table = 'cat_regionanatomica';
    protected $primaryKey = 'ID_REGION_ANATOMICA';  
    protected $fillable = [
    
        'NOMBRE_REGION',
        'ACTIVO'

    ];


}
