<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_turnoModel extends Model
{
    protected $primaryKey = 'ID_TURNO';
    protected $table = 'catergo_turno';
    protected $fillable = [
        'NOMBRE_TURNO',
        'ACTIVO'

    ];
}
