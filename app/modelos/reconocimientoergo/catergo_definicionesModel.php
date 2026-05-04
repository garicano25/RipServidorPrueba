<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_definicionesModel extends Model
{
    protected $primaryKey = 'ID_DEFINICIONES';
    protected $table = 'catergo_definiciones';
    protected $fillable = [
        'USO_DEFINICIONES',
        'CONCEPTO_DEFINICION',
        'DESCRIPCION_DEFINICION',
        'FUENTE_DEFINICION',
        'ACTIVO'

    ];
}
