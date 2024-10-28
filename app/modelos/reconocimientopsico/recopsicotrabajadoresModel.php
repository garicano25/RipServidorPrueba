<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicotrabajadoresModel extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICOTRABAJADOR';
    protected $table = 'recopsicotrabajadores';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICOTRABAJADOR_MUESTRA',
        'RECPSICOTRABAJADOR_ORDEN',
        'RECPSICOTRABAJADOR_NOMBRE',
        'RECPSICOTRABAJADOR_GENERO',
        'RECPSICOTRABAJADOR_AREA',
        'RECPSICOTRABAJADOR_CATEGORIA',
        'RECPSICOTRABAJADOR_FICHA',
        'RECPSICOTRABAJADOR_CORREO',
        'RECPSICOTRABAJADOR_SELECCIONADO',
        'RECPSICOTRABAJADOR_OBSERVACION',
        'RECPSICOTRABAJADOR_MODALIDAD'
    ];
}
