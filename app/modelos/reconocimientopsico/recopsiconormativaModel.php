<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsiconormativaModel extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICONORMATIVA';
    protected $table = 'recopsiconormativa';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICO_GUIAI',
        'RECPSICO_GUIAII',
        'RECPSICO_GUIAIII',
        'RECPSICO_TOTALTRABAJADORES',
        'RECPSICO_TIPOAPLICACION',
        'RECPSICO_TOTALAPLICACION',
        'RECPSICO_GENEROS',
        'RECPSICO_PORCENTAJEHOMBRESTRABAJO',
        'RECPSICO_PORCENTAJEMUJERESTRABAJO',
        'RECPSICO_TOTALHOMBRESTRABAJO',
        'RECPSICO_TOTALMUJERESTRABAJO',
        'RECPSICO_TOTALHOMBRESSELECCION',
        'RECPSICO_TOTALMUJERESSELECCION'
    ];
}
