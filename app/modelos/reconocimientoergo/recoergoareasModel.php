<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class recoergoareasModel extends Model
{
    protected $primaryKey = 'ID_AREA_ERGO';
    protected $table = 'recoergoareas';
    protected $fillable = [
        'RECO_ID',
        'NOMBRE_AREA_ERGO',
        'DESCRIPCION_AREA_ERGO',
        'ACTIVO'

    ];
}
