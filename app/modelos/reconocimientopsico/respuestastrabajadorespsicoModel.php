<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class respuestastrabajadorespsicoModel extends Model
{
    protected $primaryKey = 'ID_RECOPSICORESPUESTAS';
    protected $table = 'recopsicoTrabajadoresRespuestas';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICO_TRABAJADOR',
        'RECPSICO_GUIAI_RESPUESTAS',
        'RECPSICO_GUIAII_RESPUESTAS',
        'RECPSICO_GUIAIII_RESPUESTAS',
        'RECPSICO_GUIAV_ID',
    ];
}
