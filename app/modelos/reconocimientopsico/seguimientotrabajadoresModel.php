<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class seguimientotrabajadoresModel extends Model
{
    protected $primaryKey = 'ID_SEGUIMIENTOTRABAJADORES';
    protected $table = 'seguimientotrabajadores';
    protected $fillable = [
        'proyecto_id',
        'TRABAJADOR_ID',
        'TRABAJADOR_ESTADOCORREO',
        'TRABAJADOR_ESTADOCONTESTADO',
        'TRABAJADOR_ESTADOCARGADO',
        'TRABAJADOR_FECHAINICIO',
        'TRABAJADOR_FECHAFIN',
        'TRABAJADOR_FECHAAPLICACION',
        'TRABAJADOR_RUTAEVIDENCIA1',
        'TRABAJADOR_RUTAEVIDENCIA1'
    ];
}
