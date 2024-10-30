<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class controlCambiosModel extends Model
{
    protected $primaryKey = 'ID_CONTROL_CAMBIO';
    protected $table = 'controlCambios';
    protected $fillable = [
        'RECONOCIMIENTO_ID',
        'REALIZADO_ID',
        'DESCRIPCION_REALIZADO',
        'AUTORIZADO_ID',
        'DESCRIPCION_AUTORIZADO',
        'RUTA_ZIP',
        'NUMERO_VERSIONES',
        'AUTORIZADO',
    ];
}
