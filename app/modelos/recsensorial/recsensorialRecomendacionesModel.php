<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialRecomendacionesModel extends Model
{
    protected $primaryKey = 'ID_RELACION';
    protected $table = 'recsensorialRecomendaciones';
    protected $fillable = [
        'RECSENSORIAL_ID',
        'RECOMENDACION_ID'

    ];
}
