<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class proyectotrabajadoresModel extends Model
{
    protected $primaryKey = 'ID_PROYECTOTRABAJADORES';
    protected $table = 'proyectotrabajadores';
    protected $fillable = [
        'proyecto_id',
        'TRABAJADOR_ID',
        'TRABAJADOR_NOMBRE',
        'TRABAJADOR_MODALIDAD',
        'TRABAJADOR_OBSERVACION',
        'TRABAJADOR_SELECCIONADO'
    ];
}
