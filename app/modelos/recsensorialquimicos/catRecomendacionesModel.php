<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catRecomendacionesModel extends Model
{
    protected $primaryKey = 'ID_RECOMENDACION';
    protected $table = 'catRecomendaciones';
    protected $fillable = [
        'DESCRIPCION',
        'ACTIVO'
    ];
}
