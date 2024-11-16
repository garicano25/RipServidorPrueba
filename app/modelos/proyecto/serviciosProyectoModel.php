<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class serviciosProyectoModel extends Model
{
 
    protected $primaryKey = 'ID_SERVICIO_PROYECTO';
    protected $table = 'serviciosProyecto';
    protected $fillable = [
        'PROYECTO_ID',
        'HI',
        'HI_PROGRAMA',
        'HI_RECONOCIMIENTO',
        'HI_EJECUCION',
        'HI_INFORME',
        'ERGO',
        'ERGO_PROGRAMA',
        'ERGO_RECONOCIMIENTO',
        'ERGO_EJECUCION',
        'ERGO_INFORME',
        'PSICO',
        'PSICO_PROGRAMA',
        'PSICO_RECONOCIMIENTO',
        'PSICO_EJECUCION',
        'PSICO_INFORME',
        'SEGURIDAD',
        'SEGURIDAD_PROGRAMA',
        'SEGURIDAD_RECONOCIMIENTO',
        'SEGURIDAD_EJECUCION',
        'SEGURIDAD_INFORME'
    ];

    
}
