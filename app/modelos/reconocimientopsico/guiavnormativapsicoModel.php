<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class guiavnormativapsicoModel extends Model
{
    protected $primaryKey = 'ID_GUIAV';
    protected $table = 'guiavNormativa';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICO_PREGUNTA1',
        'RECPSICO_PREGUNTA2',
        'RECPSICO_PREGUNTA3',
        'RECPSICO_PREGUNTA4',
        'RECPSICO_PREGUNTA5',
        'RECPSICO_PREGUNTA6',
        'RECPSICO_PREGUNTA7',
        'RECPSICO_PREGUNTA8',
        'RECPSICO_PREGUNTA9',
        'RECPSICO_PREGUNTA10',
        'RECPSICO_PREGUNTA11',
        'RECPSICO_PREGUNTA12',
        'RECPSICO_PREGUNTA13',
    ];
}
