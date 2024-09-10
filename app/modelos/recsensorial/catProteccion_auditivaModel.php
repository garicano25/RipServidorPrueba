<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catProteccion_auditivaModel extends Model
{
    protected $primaryKey = 'ID_PROTECCION';
    protected $table = 'catProteccion_auditiva';
    protected $fillable = [
        'TIPO',
        'MODELO',
        'MARCA',
        'NRR',
        'SNR',
        'CUMPLIMIENTO',
        'H',
        'M',
        'L',
        'ATENUACIONES_JSON',
        'DESVIACIONES_JSON',
        'RUTA_PDF',
        'RUTA_IMG',
        'ACTIVO'
    ];
}
