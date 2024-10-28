<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicoguia5Model extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICOGUIA_5';
    protected $table = 'recopsicoguia_5';
    protected $fillable = [
        'RECPSICOTRABAJADOR_ID',
        'RECPSICO_ID',
        'RECPSICOTRABAJADOR_GENERO',
        'RECPSICOTRABAJADOR_EDAD',
        'RECPSICOTRABAJADOR_FNACIMIENTO',
        'RECPSICOTRABAJADOR_ESTADOCIVIL',
        'RECPSICOTRABAJADOR_ESTUDIOS',
        'RECPSICOTRABAJADOR_TIPOPUESTO',
        'RECPSICOTRABAJADOR_TIPOCONTRATACION',
        'RECPSICOTRABAJADOR_TIPOPERSONAL',
        'RECPSICOTRABAJADOR_TIPOJORNADA',
        'RECPSICOTRABAJADOR_ROTACIONTURNOS',
        'RECPSICOTRABAJADOR_TIEMPOPUESTO',
        'RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA'
    ];
}
