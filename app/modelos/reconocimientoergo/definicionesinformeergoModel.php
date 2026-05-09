<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class definicionesinformeergoModel extends Model
{
    protected $table = 'definicionesinformeergo';
    protected $primaryKey = 'ID_DEFINICION_INFORME_ERGO';
    protected $fillable = [
        'RECO_ID',
        'CATALOGO_DEFINICIONES_ID'
    ];
}
