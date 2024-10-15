<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicoproyectotrabajadoresModel extends Model
{
    protected $primaryKey = 'ID_RECOPSICOPROYECTOTRABAJADORES';
    protected $table = 'recopsicoproyectotrabajadores';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICOTRABAJADOR_ID',
        'RECPSICOTRABAJADOR_MODALIDAD',
        'RECPSICOTRABAJADOR_OBSERVACION',
        'RECPSICOTRABAJADOR_SELECCIONADO',
        'RECPSICOTRABAJADOR_ESTADOCORREO',
        'RECPSICOTRABAJADOR_ESTADOCONTESTADO',
        'RECPSICOTRABAJADOR_ESTADOCARGADO',
        'RECPSICOTRABAJADOR_FECHAINICIO',
        'RECPSICOTRABAJADOR_FECHAFIN',
        'RECPSICOTRABAJADOR_FECHAAPLICACION',
        'RECPSICOTRABAJADOR_RUTAEVIDENCIA1',
        'RECPSICOTRABAJADOR_RUTAEVIDENCIA1'
    ];
}
