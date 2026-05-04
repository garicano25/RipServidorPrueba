<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_jornada extends Model
{
    protected $primaryKey = 'ID_JORNADA';
    protected $table = 'catergo_jornada';
    protected $fillable = [
        'NOMBRE_JORNADA',
        'ACTIVO'

    ];
}
