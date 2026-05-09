<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class recomendacionesinformeergoModel extends Model
{
    protected $table ='recomendacionesinformeergo';
    protected $primaryKey ='ID_RECOMENDACIONES_INFORME_ERGO';
    protected $fillable = [
        'RECO_ID',
        'CATALOGO_RECOMENDACIONES_ID'

    ];
}
