<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class estatusReportesInformeModel extends Model
{
    protected $primaryKey = 'ID_ESTATUS_REPORTE';
    protected $table = 'estatusReportesInforme';
    protected $fillable = [
        'PROYECTO_ID',
        'POE_FINALIZADO'

    ];
}
