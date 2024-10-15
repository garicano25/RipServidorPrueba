<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeiepp extends Model
{
    //
    protected $table = 'reportebeiepp';
    protected $fillable = [
        'proyecto_id',
        'registro_id',
        'reportebeiepp_partecuerpo',
        'reportebeiepp_equipo'
    ];
}
