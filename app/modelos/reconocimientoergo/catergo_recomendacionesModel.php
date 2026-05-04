<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_recomendacionesModel extends Model
{
    protected $primaryKey = 'ID_RECOMENDACIONES';
    protected $table = 'catergo_recomendaciones';
    protected $fillable = [
        'USO_RECOMENDACIONES',
        'TIPO_RECOMENDACIONES',
        'DESCRIPCION_RECOMENDACIONES',
        'ACTIVO'

    ];
}
